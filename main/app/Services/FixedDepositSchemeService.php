<?php

namespace App\Services;

use App\Constants\ManageStatus;
use App\Models\AdminNotification;
use App\Models\FixedDepositScheme;
use App\Models\FixedDepositSchemePlan;
use App\Models\Setting;
use App\Models\User;
use App\Traits\Makeable;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class FixedDepositSchemeService extends BaseService
{
    use Makeable;

    /**
     * Validates the transaction state based on OTP requirements and fds plan.
     *
     * @param Setting $settings
     * @param array $transactionStateInformation
     * @return void
     * @throws Exception
     */
    private function validateTransactionState(Setting $settings, array $transactionStateInformation): void
    {
        $this->checkTransactionState($settings, $transactionStateInformation, 'fds_plan', 'open a FDS');
    }

    /**
     * Opens a new fixed deposit scheme (FDS) for a user.
     *
     * @param User $user
     * @param array $transactionStateInformation
     * @return void
     * @throws Exception
     */
    public function openFds(User $user, array $transactionStateInformation): void
    {
        $this->validateTransactionState(bs(), $transactionStateInformation);

        $fdsPlan      = $transactionStateInformation['fds_plan'];
        $investAmount = $transactionStateInformation['invest_amount'];

        if (!FixedDepositSchemePlan::active()->find($fdsPlan['id'])) {
            throw new Exception('The selected FDS Plan is either inactive or does not exist.');
        }

        $fds = $this->createFds($user, $fdsPlan, $investAmount);

        $user->decrement('balance', $investAmount);

        $this->recordTransaction($user, $fds, 'fds_open');

        $this->notifyAdmin($user, $fds, 'A new fixed deposit has been opened');

        $this->notifyUser($user, 'FDS_OPEN', $fds);
    }

    /**
     * Creates a new Fixed Deposit Scheme (FDS) record in the database.
     *
     * @param User $user
     * @param array $plan
     * @param float $depositAmount
     * @return FixedDepositScheme
     */
    private function createFds(User $user, array $plan, float $depositAmount): FixedDepositScheme
    {
        $perInstallment      = ($depositAmount * $plan['interest_rate']) / 100;
        $nextInstallmentDate = today()->addDays($plan['interest_payout_interval']);
        $lockedPeriod        = today()->addDays($plan['lock_in_period']);

        return $user->fixedDepositSchemes()->create([
            'plan_name'                => $plan['name'],
            'scheme_code'              => getTrx(),
            'interest_rate'            => $plan['interest_rate'],
            'deposit_amount'           => $depositAmount,
            'interest_payout_interval' => $plan['interest_payout_interval'],
            'per_installment'          => $perInstallment,
            'next_installment_date'    => $nextInstallmentDate,
            'locked_until'             => $lockedPeriod,
        ]);
    }

    /**
     * Records a transaction for various Fixed Deposit Scheme (FDS) activities.
     *
     * @param User $user
     * @param FixedDepositScheme $fds
     * @param string $reason
     * @return void
     */
    private function recordTransaction(User $user, FixedDepositScheme $fds, string $reason): void
    {
        $currency = bs('site_cur');

        if ($reason == 'fds_open') {
            $formattedAmount = showAmount($fds->deposit_amount);

            $user->transactions()->create([
                'amount'       => $fds->deposit_amount,
                'post_balance' => $user->balance,
                'trx_type'     => '-',
                'trx'          => $fds->scheme_code,
                'details'      => "A payment of $formattedAmount $currency has been made for the Fixed Deposit opening.",
                'remark'       => $reason,
            ]);
        } else {
            $formattedAmount = showAmount($fds->profit_amount);

            $user->transactions()->create([
                'amount'       => $fds->profit_amount,
                'post_balance' => $user->balance,
                'trx_type'     => '+',
                'trx'          => getTrx(),
                'details'      => "Received $formattedAmount $currency in FDS interest upon the closure of your FDS",
                'remark'       => $reason,
            ]);
        }
    }

    /**
     * Sends a notification to the admin when an FDS is opened or closed.
     *
     * @param User $user
     * @param FixedDepositScheme $fds
     * @param string $title
     * @return void
     */
    private function notifyAdmin(User $user, FixedDepositScheme $fds, string $title): void
    {
        AdminNotification::create([
            'user_id'   => $user->id,
            'title'     => $title,
            'click_url' => urlPath('admin.fds.index') . "?search=$fds->scheme_code",
        ]);
    }

    /**
     * Sends a notification to the user based on the provided template.
     *
     * @param User $user
     * @param string $template
     * @param FixedDepositScheme $fds
     * @return void
     */
    private function notifyUser(User $user, string $template, FixedDepositScheme $fds): void
    {
        $data = [
            'plan_name'      => $fds->plan_name,
            'scheme_code'    => $fds->scheme_code,
            'deposit_amount' => showAmount($fds->deposit_amount),
        ];

        $templateData = [
            'FDS_OPEN'  => [
                'per_installment'       => showAmount($fds->per_installment),
                'next_installment_date' => showDateTime($fds->next_installment_date, 'd M, Y'),
                'interest_rate'         => showAmount($fds->interest_rate),
                'locked_until'          => showDateTime($fds->locked_until, 'd M, Y'),
            ],
            'FDS_CLOSE' => [
                'profit_amount' => showAmount($fds->profit_amount),
            ]
        ];

        $notificationData = array_merge($data, $templateData[$template]);

        notify($user, $template, $notificationData);
    }

    /**
     * Fetches a paginated list of Fixed Deposit Schemes (FDS) based on the given filter.
     *
     * @param string|null $filter
     * @return LengthAwarePaginator
     */
    public function fetchFixedDepositSchemesByType(?string $filter = null): LengthAwarePaginator
    {
        $query = FixedDepositScheme::query()->with('user');

        if ($filter) $query->$filter();

        return $query->searchable(['scheme_code', 'user:firstname,lastname,account_number'])
            ->latest()
            ->paginate(getPaginate());
    }

    /**
     * Processes an installment for a Fixed Deposit Scheme (FDS).
     *
     * @param FixedDepositScheme $fds
     * @return void
     */
    public function processInstallment(FixedDepositScheme $fds): void
    {
        $fds->increment('profit_amount', $fds->per_installment);

        $fds->update([
            'next_installment_date' => $fds->next_installment_date->addDays($fds->interest_payout_interval)->toDateString(),
        ]);

        $fds->installments()->create([
            'installment_date' => $fds->next_installment_date->subDays($fds->interest_payout_interval)->toDateString(),
        ]);
    }

    /**
     * Handles the transfer of profit when closing a Fixed Deposit Scheme (FDS).
     *
     * @param User $user
     * @param FixedDepositScheme $fds
     * @return void
     */
    public function handleTransferProfit(User $user, FixedDepositScheme $fds): void
    {
        $user->increment('balance', $fds->profit_amount);

        $this->recordTransaction($user, $fds, 'fds_close');

        $fds->update([
            'transfer_at' => now(),
            'status'      => ManageStatus::FDS_CLOSED,
        ]);

        $this->notifyAdmin($user, $fds, 'A fixed deposit scheme (FDS) has been closed');

        $this->notifyUser($user, 'FDS_CLOSE', $fds);
    }
}
