<?php

namespace App\Services;

use App\Lib\FileManager;
use App\Models\Form;
use App\Models\MoneyTransfer;
use App\Models\OtherBank;
use App\Models\Setting;
use App\Models\User;
use App\Models\WireTransferSettings;
use App\Traits\Makeable;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MoneyTransferService extends BaseService
{
    use Makeable;

    /**
     * Find a user by account number.
     *
     * @param string $accountNumber
     * @return User|null
     */
    public function findUserByAccountNumber(string $accountNumber): ?User
    {
        return User::where('account_number', $accountNumber)
            ->whereNot('id', auth('web')->id())
            ->active()
            ->first();
    }

    /**
     * Validates an internal transfer for a given user and amount.
     *
     * @param User $user
     * @param float $amount
     * @return void
     * @throws Exception
     */
    public function validateInternalTransfer(User $user, float $amount): void
    {
        $perTransactionMinAmount = bs('per_transaction_min_amount');
        $perTransactionMaxAmount = bs('per_transaction_max_amount');
        $currencySymbol          = bs('cur_sym');

        // Check if the transaction amount is within the allowed per-transaction limits
        if ($amount < $perTransactionMinAmount || $amount > $perTransactionMaxAmount) {
            throw new Exception('Transaction failed: The amount must be between ' . $currencySymbol . showAmount($perTransactionMinAmount) . ' and ' . $currencySymbol . showAmount($perTransactionMaxAmount));
        }

        // Check if the transaction would exceed the daily transaction limit
        if ($this->exceedsLimit($user, $amount, 'daily', 'internal')) {
            throw new Exception('Transaction failed: The total amount for today exceeds the maximum limit.');
        }

        // Check if the transaction would exceed the monthly transaction limit
        if ($this->exceedsLimit($user, $amount, 'monthly', 'internal')) {
            throw new Exception('Transaction failed: The total amount for this month exceeds the maximum limit.');
        }
    }

    /**
     * Check if the total transaction amount (including the charge) exceeds the user's available balance
     *
     * @param float $amount
     * @param float $charge
     * @param float $balance
     * @return void
     * @throws Exception
     */
    public function ensureSufficientBalance(float $amount, float $charge, float $balance): void
    {
        if ($amount + $charge > $balance) {
            throw new Exception('Transaction failed: Insufficient balance for this transaction.');
        }
    }

    /**
     * Check if the transaction would exceed the limit
     *
     * @param User $user
     * @param float $amount
     * @param string $type
     * @param string $transferType
     * @param OtherBank|null $otherBank
     * @param WireTransferSettings|null $wireTransferSettings
     * @return bool
     */
    public function exceedsLimit(User $user, float $amount, string $type, string $transferType, ?OtherBank $otherBank = null, ?WireTransferSettings $wireTransferSettings = null): bool
    {
        $query = $user->moneyTransfers()
            ->when($transferType === 'wire', function (Builder $q) {
                $q->whereNull('beneficiary_id');
            }, function (Builder $q) use ($transferType) {
                $q->whereHas('beneficiary', function (Builder $subQuery) use ($transferType) {
                    $subQuery->where('beneficiaryable_type', '=', $transferType == 'internal' ? User::class : OtherBank::class);
                });
            })
            ->completed();

        $amountLimit = $transactionLimit = 0;

        if ($type === 'daily') {
            $query->whereDate('created_at', today());

            switch ($transferType) {
                case 'internal':
                    $amountLimit = bs('daily_transaction_max_amount');
                    break;
                case 'external':
                    $amountLimit      = $otherBank->daily_transaction_max_amount;
                    $transactionLimit = $otherBank->daily_transaction_limit;
                    break;
                default:
                    $amountLimit      = $wireTransferSettings->daily_transaction_max_amount;
                    $transactionLimit = $wireTransferSettings->daily_transaction_limit;
            }
        } elseif ($type === 'monthly') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);

            switch ($transferType) {
                case 'internal':
                    $amountLimit = bs('monthly_transaction_max_amount');
                    break;
                case 'external':
                    $amountLimit      = $otherBank->monthly_transaction_max_amount;
                    $transactionLimit = $otherBank->monthly_transaction_limit;
                    break;
                default:
                    $amountLimit      = $wireTransferSettings->monthly_transaction_max_amount;
                    $transactionLimit = $wireTransferSettings->monthly_transaction_limit;
            }
        }

        if ($transferType == 'internal') {
            $total = $query->sum('amount');

            return $amount + $total > $amountLimit;
        } else {
            $stats = $query->selectRaw('COUNT(*) AS total_count, SUM(amount) AS total_amount')->first();

            return $amount + $stats->total_amount > $amountLimit || $stats->total_count + 1 > $transactionLimit;
        }
    }

    /**
     * Validates the transaction state based on OTP requirements and transaction amount.
     *
     * @param Setting $settings
     * @param array $transactionStateInformation
     * @return void
     * @throws Exception
     */
    public function validateTransactionState(Setting $settings, array $transactionStateInformation): void
    {
        $this->checkTransactionState($settings, $transactionStateInformation, 'amount', 'proceed with the transaction');
    }

    /**
     * Get the value of an element by its name from an array.
     *
     * @param array $details
     * @param string $key
     * @return string|null
     */
    public function getElementByName(array $details, string $key): ?string
    {
        foreach ($details as $item) {
            if (isset($item['name']) && $item['name'] === $key) {
                return $item['value'] ?? null;
            }
        }

        return null;
    }

    /**
     * Removes existing files from storage based on the details provided.
     *
     * @param array $details
     * @param Request|null $request
     * @return void
     */
    public function removeExistingFiles(array $details, ?Request $request = null): void
    {
        foreach ($details as $field) {
            if ($request) {
                $fieldName = titleToKey($field['name']);
                $condition = $field['type'] == 'file' && !is_null($field['value']) && $request->hasFile($fieldName);
            } else {
                $condition = $field['type'] == 'file' && !empty($field['value']);
            }

            if ($condition) {
                $fileValue = decrypt($field['value']);
                $filePath  = getFilePath('verify') . "/$fileValue";

                (new FileManager)->removeFile($filePath);
            }
        }
    }

    /**
     * Validates the external transfer based on user, amount, and bank-specific limits.
     *
     * @param User $user
     * @param float $amount
     * @param OtherBank $otherBank
     * @return void
     * @throws Exception
     */
    public function validateExternalTransfer(User $user, float $amount, OtherBank $otherBank): void
    {
        $perTransactionMinAmount = $otherBank->per_transaction_min_amount;
        $perTransactionMaxAmount = $otherBank->per_transaction_max_amount;
        $currencySymbol          = bs('cur_sym');

        // Check if the transaction amount is within the allowed per-transaction limits
        if ($amount < $perTransactionMinAmount || $amount > $perTransactionMaxAmount) {
            throw new Exception('Transaction failed: The amount must be between ' . $currencySymbol . showAmount($perTransactionMinAmount) . ' and ' . $currencySymbol . showAmount($perTransactionMaxAmount));
        }

        // Check if the transaction would exceed the daily transaction limit
        if ($this->exceedsLimit($user, $amount, 'daily', 'external', $otherBank)) {
            throw new Exception('Transaction failed: The total amount or the total number of transactions for today exceeds the maximum limit.');
        }

        // Check if the transaction would exceed the monthly transaction limit
        if ($this->exceedsLimit($user, $amount, 'monthly', 'external', $otherBank)) {
            throw new Exception('Transaction failed: The total amount or the total number of transactions for this month exceeds the maximum limit.');
        }
    }

    /**
     * Validates the wire transfer based on wire transfer settings.
     *
     * @param User $user
     * @param float $amount
     * @param WireTransferSettings $wireTransferSettings
     * @return void
     * @throws Exception
     */
    public function validateWireTransfer(User $user, float $amount, WireTransferSettings $wireTransferSettings): void
    {
        $perTransactionMinAmount = $wireTransferSettings->per_transaction_min_amount;
        $perTransactionMaxAmount = $wireTransferSettings->per_transaction_max_amount;
        $currencySymbol          = bs('cur_sym');

        // Check if the transaction amount is within the allowed per-transaction limits
        if ($amount < $perTransactionMinAmount || $amount > $perTransactionMaxAmount) {
            throw new Exception('Transaction failed: The amount must be between ' . $currencySymbol . showAmount($perTransactionMinAmount) . ' and ' . $currencySymbol . showAmount($perTransactionMaxAmount));
        }

        // Check if the transaction would exceed the daily transaction limit
        if ($this->exceedsLimit($user, $amount, 'daily', 'wire', wireTransferSettings: $wireTransferSettings)) {
            throw new Exception('Transaction failed: The total amount or the total number of transactions for today exceeds the maximum limit.');
        }

        // Check if the transaction would exceed the monthly transaction limit
        if ($this->exceedsLimit($user, $amount, 'monthly', 'wire', wireTransferSettings: $wireTransferSettings)) {
            throw new Exception('Transaction failed: The total amount or the total number of transactions for this month exceeds the maximum limit.');
        }
    }

    /**
     * Process wire transfer payload by handling file uploads and extracting field values.
     *
     * @param array $payload
     * @return array
     * @throws Exception
     */
    public function processWireTransferPayload(array $payload): array
    {
        $formData = Form::where('act', '=', 'wire_transfer')->value('form_data');

        return $this->processFormFieldData($formData, $payload);
    }

    /**
     * This method retrieves money transfer records filtered by status
     * (e.g., 'pending', 'completed', 'failed') or type (e.g., 'internal', 'external').
     * If no filter is provided, it fetches all records.
     *
     * @param string|null $filter
     * @return LengthAwarePaginator
     */
    public function fetchMoneyTransfersByType(?string $filter = null): LengthAwarePaginator
    {
        $query = MoneyTransfer::query()->with(['user', 'beneficiary.beneficiaryable']);

        if ($filter) {
            if (in_array($filter, ['pending', 'completed', 'failed'])) {
                $query->$filter();
            } elseif (in_array($filter, ['internal', 'external'])) {
                $query->whereHas('beneficiary', function (Builder $q) use ($filter) {
                    if ($filter == 'internal') {
                        $q->where('beneficiaryable_type', '=', User::class);
                    } else {
                        $q->where('beneficiaryable_type', '=', OtherBank::class);
                    }
                });
            } else {
                $query->whereNull('beneficiary_id');
            }
        }

        $moneyTransfers = $query->searchable(['trx', 'user:firstname,lastname,account_number', 'beneficiary:details'])
            ->latest()
            ->paginate(getPaginate());

        $siteName = bs('site_name');

        // Transform the collection with additional data
        $moneyTransfers->getCollection()->transform(function ($moneyTransfer) use ($siteName) {
            $beneficiary = $moneyTransfer->beneficiary;

            if ($beneficiary) {
                $details = $beneficiary->details;

                if ($beneficiary->beneficiaryable_type == User::class) {
                    $moneyTransfer->receiver_account_name   = $details['account_name'];
                    $moneyTransfer->receiver_account_number = $details['account_number'];
                    $moneyTransfer->bank_name               = $siteName;
                } else {
                    $moneyTransfer->receiver_account_name   = $this->getElementByName($details, 'Account Name');
                    $moneyTransfer->receiver_account_number = $this->getElementByName($details, 'Account Number');
                    $moneyTransfer->bank_name               = $beneficiary->beneficiaryable->name;
                }
            } else {
                $payload                                = $moneyTransfer->wire_transfer_payload;
                $moneyTransfer->receiver_account_name   = $this->getElementByName($payload, 'Account Name');
                $moneyTransfer->receiver_account_number = $this->getElementByName($payload, 'Account Number');
            }

            return $moneyTransfer;
        });

        return $moneyTransfers;
    }

    /**
     * Send a notification for a money transfer based on its status.
     *
     * @param MoneyTransfer $moneyTransfer
     * @param string $status
     * @return void
     */
    public function sendMoneyTransferNotification(MoneyTransfer $moneyTransfer, string $status): void
    {
        $moneyTransfer->load(['user', 'beneficiary.beneficiaryable']);

        if ($moneyTransfer->beneficiary_id) {
            $details  = $moneyTransfer->beneficiary->details;
            $template = "EXTERNAL_TRANSFER_$status";
            $bankName = $moneyTransfer->beneficiary->beneficiaryable->name;
        } else {
            $details  = $moneyTransfer->wire_transfer_payload;
            $template = "WIRE_TRANSFER_$status";
            $bankName = null;
        }

        notify($moneyTransfer->user, $template, [
            'recipient_account_name'   => MoneyTransferService::make()->getElementByName($details, 'Account Name'),
            'recipient_account_number' => MoneyTransferService::make()->getElementByName($details, 'Account Number'),
            'external_bank'            => $bankName,
            'amount'                   => showAmount($moneyTransfer->amount),
            'charge'                   => showAmount($moneyTransfer->charge),
            'trx'                      => $moneyTransfer->trx,
            'reason'                   => $status === 'FAIL' ? $moneyTransfer->rejection_reason : null,
        ]);
    }

    /**
     * Refund the total transfer amount (including charges) to the user's balance.
     *
     * @param MoneyTransfer $moneyTransfer
     * @return void
     */
    public function refundUser(MoneyTransfer $moneyTransfer): void
    {
        $user            = $moneyTransfer->user;
        $totalAmount     = $moneyTransfer->amount + $moneyTransfer->charge;
        $formattedAmount = showAmount($totalAmount);
        $siteCurrency    = bs('site_cur');

        $user->increment('balance', $totalAmount);

        $user->transactions()->create([
            'amount'       => $totalAmount,
            'post_balance' => $user->balance,
            'trx_type'     => '+',
            'trx'          => getTrx(),
            'details'      => "Refunded $formattedAmount $siteCurrency to the account as the transfer request failed.",
            'remark'       => $moneyTransfer->beneficiary_id ? 'external_bank_transfer_refund' : 'wire_transfer_refund',
        ]);
    }
}
