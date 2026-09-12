<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\BeneficiaryRequest;
use App\Http\Requests\MoneyTransferRequest;
use App\Http\Requests\WireTransferRequest;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Beneficiary;
use App\Models\MoneyTransfer;
use App\Models\OtherBank;
use App\Models\User;
use App\Models\WireTransferSettings;
use App\Services\MoneyTransferService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class MoneyTransferController extends Controller
{
    public function withinBank()
    {
        $pageTitle     = 'Transfer Money Within ' . bs('site_name');
        $user          = auth('web')->user();
        $beneficiaries = Beneficiary::where([
            'user_id'              => $user->id,
            'beneficiaryable_type' => User::class
        ])
            ->latest()
            ->paginate(getPaginate());

        return view("{$this->activeTheme}user.moneyTransfer.ownBank", compact('pageTitle', 'user', 'beneficiaries'));
    }

    public function checkAccount(Request $request)
    {
        $account = MoneyTransferService::make()->findUserByAccountNumber($request->input('account_number'));

        if (!$account) {
            return response()->json([
                'message' => 'No such account found!',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'name' => $account->fullname
        ], Response::HTTP_OK);
    }

    public function transferWithinBank(MoneyTransferRequest $request)
    {
        $validated = $request->validated();
        $user      = auth('web')->user();

        try {
            MoneyTransferService::make()->validateInternalTransfer($user, $validated['amount']);

            $settings = bs();
            $charge   = $settings->fixed_charge + (($settings->percentage_charge * $validated['amount']) / 100);

            MoneyTransferService::make()->ensureSufficientBalance($validated['amount'], $charge, $user->balance);

            $beneficiaryId = Beneficiary::where([
                'user_id'              => $user->id,
                'beneficiaryable_id'   => $validated['beneficiaryable_id'],
                'beneficiaryable_type' => User::class
            ])
                ->pluck('id')
                ->first();

            $transactionStateInformation = [
                'amount'         => $validated['amount'],
                'charge'         => $charge,
                'beneficiary_id' => $beneficiaryId,
            ];

            // Check if OTP is required
            if ($settings->email_based_otp || $settings->sms_based_otp) {
                MoneyTransferService::make()->processOTP(
                    $validated['authorization_mode'],
                    $user,
                    $transactionStateInformation,
                    'user.money.transfer.within.bank.transfer.finalize'
                );

                return to_route('user.otp.form');
            }

            session()->put('transaction_state_information', $transactionStateInformation);

            return to_route('user.money.transfer.within.bank.transfer.finalize');
        } catch (Exception $exception) {
            return to_route('user.money.transfer.within.bank')->with('toasts', [
                ['error', $exception->getMessage()],
            ]);
        }
    }

    public function finalizeTransferWithinBank()
    {
        $settings                    = bs();
        $transactionStateInformation = session('transaction_state_information', []);
        $transactionAmount           = $transactionStateInformation['amount'];
        $transactionCharge           = $transactionStateInformation['charge'];

        try {
            DB::transaction(function () use ($settings, $transactionStateInformation, $transactionAmount, $transactionCharge) {
                MoneyTransferService::make()->validateTransactionState($settings, $transactionStateInformation);

                $sender      = User::lockForUpdate()->findOrFail(auth('web')->id());
                $beneficiary = Beneficiary::with('beneficiaryable')->findOrFail($transactionStateInformation['beneficiary_id']);
                $receiver    = $beneficiary->beneficiaryable;

                // Record the money transfer in the database
                $moneyTransfer = $sender->moneyTransfers()->create([
                    'beneficiary_id' => $beneficiary->id,
                    'trx'            => getTrx(),
                    'amount'         => $transactionAmount,
                    'charge'         => $transactionCharge,
                    'status'         => ManageStatus::MONEY_TRANSFER_COMPLETED
                ]);

                // Deduct the total amount (transfer amount + charge) from the sender's balance
                $sender->decrement('balance', ($transactionAmount + $transactionCharge));

                $formattedAmount = showAmount($transactionAmount);

                // Log the transaction for the sender
                $sender->transactions()->create([
                    'amount'       => $transactionAmount,
                    'charge'       => $transactionCharge,
                    'post_balance' => $sender->balance,
                    'trx_type'     => '-',
                    'trx'          => $moneyTransfer->trx,
                    'details'      => "Transferred $formattedAmount $settings->site_cur to {$beneficiary->details['short_name']}'s account.",
                    'remark'       => 'internal_bank_transfer',
                ]);

                // Add the transferred amount to the receiver's balance
                $receiver->increment('balance', $transactionAmount);

                // Log the transaction for the receiver
                $receiver->transactions()->create([
                    'amount'       => $transactionAmount,
                    'post_balance' => $receiver->balance,
                    'trx_type'     => '+',
                    'trx'          => $moneyTransfer->trx,
                    'details'      => "Received $formattedAmount $settings->site_cur from $sender->firstname's account.",
                    'remark'       => 'received_money',
                ]);

                // Notify the sender about the successful transfer
                notify($sender, 'INTERNAL_TRANSFER_SEND_MONEY', [
                    'receiver'     => $receiver->fullname,
                    'amount'       => $formattedAmount,
                    'charge'       => showAmount($transactionCharge),
                    'post_balance' => showAmount($sender->balance),
                    'trx'          => $moneyTransfer->trx,
                ]);

                // Notify the receiver about the received funds
                notify($receiver, 'INTERNAL_TRANSFER_RECEIVE_MONEY', [
                    'sender'       => $sender->fullname,
                    'amount'       => $formattedAmount,
                    'post_balance' => showAmount($receiver->balance),
                    'trx'          => $moneyTransfer->trx,
                ]);

                session()->forget('transaction_state_information');
            });

            return to_route('user.money.transfer.history')->with('toasts', [
                ['success', 'The within bank transfer was successfully completed.']
            ]);
        } catch (Throwable $exception) {
            return to_route('user.money.transfer.within.bank')->with('toasts', [
                ['error', $exception->getMessage()]
            ]);
        }
    }

    public function otherBank()
    {
        $pageTitle     = 'Transfer Money To Other Bank';
        $user          = auth('web')->user();
        $beneficiaries = Beneficiary::with('beneficiaryable')->where([
            'user_id'              => $user->id,
            'beneficiaryable_type' => OtherBank::class
        ])
            ->latest()
            ->paginate(getPaginate());

        $beneficiaries->getCollection()->transform(function ($beneficiary) {
            $details                     = $beneficiary->details;
            $beneficiary->account_name   = MoneyTransferService::make()->getElementByName($details, 'Account Name');
            $beneficiary->account_number = MoneyTransferService::make()->getElementByName($details, 'Account Number');
            $beneficiary->short_name     = MoneyTransferService::make()->getElementByName($details, 'Short Name');

            return $beneficiary;
        });

        $otherBanks = OtherBank::with('form')->active()->orderBy('name')->get();

        return view("{$this->activeTheme}user.moneyTransfer.otherBank", compact('pageTitle', 'user', 'beneficiaries', 'otherBanks'));
    }

    public function otherBankForm(OtherBank $otherBank)
    {
        if (!$otherBank->status) {
            return response()->json([
                'message' => 'The selected bank is currently inactive or unavailable.'
            ], Response::HTTP_FORBIDDEN);
        }

        $otherBank->load('form');

        $form = $otherBank->form;

        return response()->json([
            'html' => view('components.phinixForm', compact('form'))->render(),
        ], Response::HTTP_OK);
    }

    public function transferToOtherBank(MoneyTransferRequest $request)
    {
        $validated = $request->validated();
        $user      = auth('web')->user();

        $beneficiary = Beneficiary::with('beneficiaryable')
            ->where([
                'user_id'              => $user->id,
                'beneficiaryable_id'   => $validated['beneficiaryable_id'],
                'beneficiaryable_type' => OtherBank::class
            ])
            ->first();

        $bank = $beneficiary->beneficiaryable;

        try {
            MoneyTransferService::make()->validateExternalTransfer($user, $validated['amount'], $bank);

            $charge = $bank->fixed_charge + (($bank->percentage_charge * $validated['amount']) / 100);

            MoneyTransferService::make()->ensureSufficientBalance($validated['amount'], $charge, $user->balance);

            // Check if the bank is inactive and prevent further actions
            if (!$bank->status) {
                return back()->with('toasts', [
                    ['error', 'Transaction failed: The selected bank is currently inactive.']
                ]);
            }

            $transactionStateInformation = [
                'amount'         => $validated['amount'],
                'charge'         => $charge,
                'beneficiary_id' => $beneficiary->id,
            ];

            $settings = bs();

            // Check if OTP is required
            if ($settings->email_based_otp || $settings->sms_based_otp) {
                MoneyTransferService::make()->processOTP(
                    $validated['authorization_mode'],
                    $user,
                    $transactionStateInformation,
                    'user.money.transfer.other.bank.transfer.finalize'
                );

                return to_route('user.otp.form');
            }

            session()->put('transaction_state_information', $transactionStateInformation);

            return to_route('user.money.transfer.other.bank.transfer.finalize');
        } catch (Exception $exception) {
            return to_route('user.money.transfer.other.bank')->with('toasts', [
                ['error', $exception->getMessage()],
            ]);
        }
    }

    public function finalizeTransferToOtherBank()
    {
        $settings                    = bs();
        $transactionStateInformation = session('transaction_state_information', []);
        $transactionAmount           = $transactionStateInformation['amount'];
        $transactionCharge           = $transactionStateInformation['charge'];

        try {
            DB::transaction(function () use ($settings, $transactionStateInformation, $transactionAmount, $transactionCharge) {
                MoneyTransferService::make()->validateTransactionState($settings, $transactionStateInformation);

                $sender      = User::lockForUpdate()->findOrFail(auth('web')->id());
                $beneficiary = Beneficiary::with('beneficiaryable')->findOrFail($transactionStateInformation['beneficiary_id']);

                // Record the money transfer in the database
                $moneyTransfer = $sender->moneyTransfers()->create([
                    'beneficiary_id' => $beneficiary->id,
                    'trx'            => getTrx(),
                    'amount'         => $transactionAmount,
                    'charge'         => $transactionCharge,
                    'status'         => ManageStatus::MONEY_TRANSFER_PENDING
                ]);

                // Deduct the total amount (transfer amount + charge) from the sender's balance
                $sender->decrement('balance', ($transactionAmount + $transactionCharge));

                $formattedAmount    = showAmount($transactionAmount);
                $beneficiaryDetails = $beneficiary->details;
                $shortName          = MoneyTransferService::make()->getElementByName($beneficiaryDetails, 'Short Name');

                // Log the transaction for the sender
                $sender->transactions()->create([
                    'amount'       => $transactionAmount,
                    'charge'       => $transactionCharge,
                    'post_balance' => $sender->balance,
                    'trx_type'     => '-',
                    'trx'          => $moneyTransfer->trx,
                    'details'      => "Transfer request of $formattedAmount $settings->site_cur has been made to $shortName's account at {$beneficiary->beneficiaryable->name}.",
                    'remark'       => 'external_bank_transfer',
                ]);

                // Notify the sender about the transfer request
                notify($sender, 'EXTERNAL_TRANSFER_REQUEST', [
                    'sender_account_name'      => $sender->fullname,
                    'sender_account_number'    => $sender->account_number,
                    'recipient_account_name'   => MoneyTransferService::make()->getElementByName($beneficiaryDetails, 'Account Name'),
                    'recipient_account_number' => MoneyTransferService::make()->getElementByName($beneficiaryDetails, 'Account Number'),
                    'external_bank'            => $beneficiary->beneficiaryable->name,
                    'amount'                   => $formattedAmount,
                    'charge'                   => showAmount($transactionCharge),
                    'trx'                      => $moneyTransfer->trx,
                ]);

                // Notify the admin
                AdminNotification::create([
                    'user_id'   => $sender->id,
                    'title'     => 'A new external bank transfer request has been initiated',
                    'click_url' => urlPath('admin.money.transfers.pending'),
                ]);

                session()->forget('transaction_state_information');
            });

            return to_route('user.money.transfer.history')->with('toasts', [
                ['success', 'The request for the other bank transfer has been successfully initiated.']
            ]);
        } catch (Throwable $exception) {
            return to_route('user.money.transfer.other.bank')->with('toasts', [
                ['error', $exception->getMessage()]
            ]);
        }
    }

    public function storeBeneficiary(BeneficiaryRequest $request)
    {
        $validated         = $request->validated();
        $beneficiaryableId = $beneficiaryableType = $details = null;

        if ($validated['beneficiary_type'] == 'own_bank') {
            if (!bs('internal_bank_transfer')) {
                return to_route('user.home')->with('toasts', [
                    ['error', 'Cannot add beneficiary: Within Bank Transfer is disabled.']
                ]);
            }

            $account = MoneyTransferService::make()->findUserByAccountNumber($validated['account_number']);

            if (!$account) {
                return back()->with('toasts', [
                    ['error', 'No such account found!']
                ]);
            }

            $beneficiaryableId   = $account->id;
            $beneficiaryableType = User::class;
            $details             = [
                'account_number' => $account->account_number,
                'account_name'   => $account->fullname,
                'short_name'     => $validated['short_name'],
            ];
        } elseif ($validated['beneficiary_type'] == 'other_bank') {
            if (!bs('external_bank_transfer')) {
                return to_route('user.home')->with('toasts', [
                    ['error', 'Cannot add beneficiary: Other Bank Transfer is disabled.']
                ]);
            }

            $otherBank         = OtherBank::with('form')->active()->find($validated['other_bank']);
            $processedFormData = (new FormProcessor)->processFormData($request, $otherBank->form->form_data);

            $beneficiaryableId   = $otherBank->id;
            $beneficiaryableType = OtherBank::class;
            $details             = $processedFormData;
        }

        Beneficiary::create([
            'user_id'              => auth('web')->id(),
            'beneficiaryable_id'   => $beneficiaryableId,
            'beneficiaryable_type' => $beneficiaryableType,
            'details'              => json_encode($details),
        ]);

        return back()->with('toasts', [
            ['success', 'Beneficiary added successfully!']
        ]);
    }

    public function updateBeneficiary(BeneficiaryRequest $request, Beneficiary $beneficiary)
    {
        $validated         = $request->validated();
        $beneficiaryableId = $details = null;

        if ($validated['beneficiary_type'] == 'own_bank') {
            $account = MoneyTransferService::make()->findUserByAccountNumber($validated['account_number']);

            if (!$account) {
                return back()->with('toasts', [
                    ['error', 'No such account found!']
                ]);
            }

            $beneficiaryableId = $account->id;
            $details           = [
                'account_number' => $account->account_number,
                'account_name'   => $account->fullname,
                'short_name'     => $validated['short_name'],
            ];
        } elseif ($validated['beneficiary_type'] == 'other_bank') {
            $otherBank = OtherBank::with('form')->active()->find($validated['other_bank']);

            // Check if the selected other bank has changed
            if ($beneficiary->beneficiaryable_id != $validated['other_bank']) {
                // Remove all files associated with the old other bank
                MoneyTransferService::make()->removeExistingFiles($beneficiary->details);
            } else {
                // Remove existing file if a new file is uploaded
                MoneyTransferService::make()->removeExistingFiles($beneficiary->details, $request);
            }

            $processedFormData = (new FormProcessor)->processFormData($request, $otherBank->form->form_data);

            if ($beneficiary->beneficiaryable_id == $validated['other_bank']) {
                // Retain existing file values if no new file is uploaded
                $existingDetails = collect($beneficiary->details);

                foreach ($processedFormData as &$formField) {
                    if ($formField['type'] == 'file' && empty($formField['value'])) {
                        $existingFileField = $existingDetails->firstWhere('name', $formField['name']);

                        if ($existingFileField['value']) $formField['value'] = decrypt($existingFileField['value']);
                    }
                }

                // Break the reference after the loop
                unset($formField);
            }

            $beneficiaryableId = $otherBank->id;
            $details           = $processedFormData;
        }

        $beneficiary->update([
            'beneficiaryable_id' => $beneficiaryableId,
            'details'            => json_encode($details),
        ]);

        return back()->with('toasts', [
            ['success', 'Beneficiary updated successfully!']
        ]);
    }

    public function downloadBeneficiaryFile(Request $request, Beneficiary $beneficiary)
    {
        if ($beneficiary->user_id != auth('web')->id()) abort(Response::HTTP_FORBIDDEN);

        $file = decrypt($request->query('data'));

        try {
            return MoneyTransferService::make()->downloadFileFromPayload($beneficiary->details, $file);
        } catch (Exception $exception) {
            return back()->with('toasts', [
                ['error', $exception->getMessage()]
            ]);
        }
    }

    public function wireTransfer()
    {
        $pageTitle            = 'Wire Transfer';
        $user                 = auth('web')->user();
        $wireTransferSettings = WireTransferSettings::with('form')->first();

        if (!$wireTransferSettings) {
            return back()->with('toasts', [
                ['error', 'Wire transfer settings are not configured. Please contact support.']
            ]);
        }

        return view("{$this->activeTheme}user.moneyTransfer.wireTransfer", compact('pageTitle', 'user', 'wireTransferSettings'));
    }

    public function submitWireTransfer(WireTransferRequest $request)
    {
        $validated            = $request->validated();
        $user                 = auth('web')->user();
        $wireTransferSettings = WireTransferSettings::with('form')->firstOrFail();

        try {
            MoneyTransferService::make()->validateWireTransfer($user, $validated['amount'], $wireTransferSettings);

            $charge = $wireTransferSettings->fixed_charge + (($wireTransferSettings->percentage_charge * $validated['amount']) / 100);

            MoneyTransferService::make()->ensureSufficientBalance($validated['amount'], $charge, $user->balance);

            // Store file temporarily if uploaded
            foreach ($wireTransferSettings->form->form_data as $field) {
                if ($field->type == 'file' && $request->hasFile($field->label)) {
                    $uploadedFile             = $request->file($field->label);
                    $fileName                 = uniqid() . time() . '.' . $uploadedFile->getClientOriginalExtension();
                    $validated[$field->label] = $uploadedFile->storeAs('temp_uploads', $fileName);
                }
            }

            $transactionStateInformation = [
                'amount'                => $validated['amount'],
                'charge'                => $charge,
                'wire_transfer_payload' => array_diff_key($validated, array_flip(['amount', 'authorization_mode'])),
            ];

            $settings = bs();

            // Check if OTP is required
            if ($settings->email_based_otp || $settings->sms_based_otp) {
                MoneyTransferService::make()->processOTP(
                    $validated['authorization_mode'],
                    $user,
                    $transactionStateInformation,
                    'user.money.transfer.wire.transfer.finalize'
                );

                return to_route('user.otp.form');
            }

            session()->put('transaction_state_information', $transactionStateInformation);

            return to_route('user.money.transfer.wire.transfer.finalize');
        } catch (Exception $exception) {
            return to_route('user.money.transfer.wire.transfer')->with('toasts', [
                ['error', $exception->getMessage()],
            ]);
        }
    }

    public function finalizeWireTransfer()
    {
        $settings                    = bs();
        $transactionStateInformation = session('transaction_state_information', []);
        $transactionAmount           = $transactionStateInformation['amount'];
        $transactionCharge           = $transactionStateInformation['charge'];

        try {
            DB::transaction(function () use ($settings, $transactionStateInformation, $transactionAmount, $transactionCharge) {
                MoneyTransferService::make()->validateTransactionState($settings, $transactionStateInformation);

                $sender              = User::lockForUpdate()->findOrFail(auth('web')->id());
                $wireTransferPayload = MoneyTransferService::make()
                    ->processWireTransferPayload($transactionStateInformation['wire_transfer_payload']);

                // Record the money transfer in the database
                $moneyTransfer = $sender->moneyTransfers()->create([
                    'trx'                   => getTrx(),
                    'amount'                => $transactionAmount,
                    'charge'                => $transactionCharge,
                    'wire_transfer_payload' => json_encode($wireTransferPayload),
                    'status'                => ManageStatus::MONEY_TRANSFER_PENDING
                ]);

                // Deduct the total amount (transfer amount + charge) from the sender's balance
                $sender->decrement('balance', ($transactionAmount + $transactionCharge));

                $formattedAmount = showAmount($transactionAmount);

                // Log the transaction for the sender
                $sender->transactions()->create([
                    'amount'       => $transactionAmount,
                    'charge'       => $transactionCharge,
                    'post_balance' => $sender->balance,
                    'trx_type'     => '-',
                    'trx'          => $moneyTransfer->trx,
                    'details'      => "A wire transfer request of $formattedAmount $settings->site_cur has been initiated.",
                    'remark'       => 'wire_transfer',
                ]);

                $payloadData = $moneyTransfer->wire_transfer_payload;

                // Notify the sender about the transfer request
                notify($sender, 'WIRE_TRANSFER_REQUEST', [
                    'sender_account_name'      => $sender->fullname,
                    'sender_account_number'    => $sender->account_number,
                    'recipient_account_name'   => MoneyTransferService::make()->getElementByName($payloadData, 'Account Name'),
                    'recipient_account_number' => MoneyTransferService::make()->getElementByName($payloadData, 'Account Number'),
                    'amount'                   => $formattedAmount,
                    'charge'                   => showAmount($transactionCharge),
                    'trx'                      => $moneyTransfer->trx,
                ]);

                // Notify the admin
                AdminNotification::create([
                    'user_id'   => $sender->id,
                    'title'     => 'A new wire transfer request has been initiated',
                    'click_url' => urlPath('admin.money.transfers.pending'),
                ]);

                session()->forget('transaction_state_information');
            });

            return to_route('user.money.transfer.history')->with('toasts', [
                ['success', 'The request for the wire transfer has been successfully initiated.']
            ]);
        } catch (Throwable $exception) {
            return to_route('user.money.transfer.wire.transfer')->with('toasts', [
                ['error', $exception->getMessage()]
            ]);
        }
    }

    public function history()
    {
        $pageTitle      = 'Transfer History';
        $user           = auth('web')->user();
        $moneyTransfers = $user->moneyTransfers()->with('beneficiary.beneficiaryable')
            ->searchable(['trx'])
            ->dateFilter()
            ->latest()
            ->paginate(getPaginate());

        $siteName = bs('site_name');

        $moneyTransfers->getCollection()->transform(function ($moneyTransfer) use ($siteName) {
            $beneficiary = $moneyTransfer->beneficiary;

            if ($beneficiary) {
                if ($beneficiary->beneficiaryable_type == User::class) {
                    $moneyTransfer['bank_name'] = $siteName;
                } else {
                    $details                         = $beneficiary->details;
                    $moneyTransfer['account_name']   = MoneyTransferService::make()->getElementByName($details, 'Account Name');
                    $moneyTransfer['account_number'] = MoneyTransferService::make()->getElementByName($details, 'Account Number');
                    $moneyTransfer['bank_name']      = $beneficiary->beneficiaryable->name;
                }
            } else {
                $payload                         = $moneyTransfer->wire_transfer_payload;
                $moneyTransfer['account_name']   = MoneyTransferService::make()->getElementByName($payload, 'Account Name');
                $moneyTransfer['account_number'] = MoneyTransferService::make()->getElementByName($payload, 'Account Number');
            }

            return $moneyTransfer;
        });

        return view("{$this->activeTheme}user.moneyTransfer.history", compact('pageTitle', 'user', 'moneyTransfers'));
    }

    public function downloadMoneyTransferFile(Request $request, MoneyTransfer $moneyTransfer)
    {
        if ($moneyTransfer->user()->isNot(auth('web')->user())) abort(Response::HTTP_FORBIDDEN);

        $file = decrypt($request->query('data'));

        try {
            return MoneyTransferService::make()->downloadFileFromPayload($moneyTransfer->wire_transfer_payload, $file);
        } catch (Exception $exception) {
            return back()->with('toasts', [
                ['error', $exception->getMessage()]
            ]);
        }
    }

    public function export(MoneyTransfer $moneyTransfer)
    {
        $user = auth('web')->user();

        if ($moneyTransfer->user()->isNot($user)) abort(Response::HTTP_FORBIDDEN);

        if ($moneyTransfer->status != ManageStatus::MONEY_TRANSFER_COMPLETED) {
            return back()->with('toasts', [
                ['error', 'The money transfer cannot be exported because the transaction is not completed.']
            ]);
        }

        $moneyTransfer->load('beneficiary.beneficiaryable');

        $beneficiary = $moneyTransfer->beneficiary;

        if ($beneficiary) {
            if ($beneficiary->beneficiaryable_type == User::class) {
                $moneyTransfer['bank_name'] = bs('site_name');
            } else {
                $beneficiaryDetails              = $beneficiary->details;
                $moneyTransfer['account_name']   = MoneyTransferService::make()->getElementByName($beneficiaryDetails, 'Account Name');
                $moneyTransfer['account_number'] = MoneyTransferService::make()->getElementByName($beneficiaryDetails, 'Account Number');
                $moneyTransfer['bank_name']      = $beneficiary->beneficiaryable->name;
            }
        } else {
            $payload                         = $moneyTransfer->wire_transfer_payload;
            $moneyTransfer['account_name']   = MoneyTransferService::make()->getElementByName($payload, 'Account Name');
            $moneyTransfer['account_number'] = MoneyTransferService::make()->getElementByName($payload, 'Account Number');
        }

        $contactElements = getSiteData('contact_us.element', false, null, true);
        $companyInfo     = [];

        foreach ($contactElements as $contactElement) {
            $companyInfo[$contactElement->data_info->heading] = $contactElement->data_info->data;
        }

        $pdf      = Pdf::loadView('pdf.moneyTransfer', compact('moneyTransfer', 'user', 'companyInfo'));
        $fileName = "Money-Transfer_$moneyTransfer->trx.pdf";

        return $pdf->download($fileName);
    }
}
