<?php

namespace App\Services;

use App\Constants\ManageStatus;
use App\Models\AdminNotification;
use App\Models\DepositPensionScheme;
use App\Models\DepositPensionSchemePlan;
use App\Models\Installment;
use App\Models\Setting;
use App\Models\User;
use App\Traits\Makeable;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class DepositPensionSchemeService extends BaseService
{
    use Makeable;

    /**
     * Validates the transaction state based on OTP requirements and dps plan.
     *
     * @param Setting $settings
     * @param array $transactionStateInformation
     * @return void
     * @throws Exception
     */
    private function validateTransactionState(Setting $settings, array $transactionStateInformation): void
    {
        $this->checkTransactionState($settings, $transactionStateInformation, 'dps_plan', 'open a DPS');
    }

    /**
     * Opens a new deposit pension scheme (DPS) for a user.
     *
     * @param User $user
     * @param array $transactionStateInformation
     * @return void
     * @throws Exception
     */
    public function openDps(User $user, array $transactionStateInformation): void
    {
        $this->validateTransactionState(bs(), $transactionStateInformation);

        $dpsPlan = $transactionStateInformation['dps_plan'];

        if (!DepositPensionSchemePlan::active()->find($dpsPlan['id'])) {
            throw new Exception('The selected DPS Plan is either inactive or does not exist.');
        }

        $dps = $this->createDps($user, $dpsPlan);

        $this->saveInstallments($dps);

        $user->decrement('balance', $dps->per_installment);

        $this->recordTransaction($user, $dps, 'installment_payment');

        $dps->oldestInstallment()->update([
            'given_at' => now()
        ]);

        $this->notifyAdmin($user, $dps, 'A new deposit pension scheme (DPS) has been opened');

        $this->notifyUser($user, 'DPS_OPEN', $dps);
    }

    /**
     * Creates a new Deposit Pension Scheme (DPS) record in the database.
     *
     * @param User $user
     * @param array $plan
     * @return DepositPensionScheme
     */
    private function createDps(User $user, array $plan): DepositPensionScheme
    {
        $perInstallmentLateFee = $plan['fixed_charge'] + (($plan['per_installment'] * $plan['percentage_charge']) / 100);

        return $user->depositPensionSchemes()->create([
            'plan_name'                => $plan['name'],
            'scheme_code'              => getTrx(),
            'per_installment'          => $plan['per_installment'],
            'installment_interval'     => $plan['installment_interval'],
            'total_installment'        => $plan['total_installment'],
            'given_installment'        => 1,
            'total_deposit_amount'     => $plan['total_deposit_amount'],
            'interest_rate'            => $plan['interest_rate'],
            'profit_amount'            => $plan['profit_amount'],
            'maturity_amount'          => $plan['maturity_amount'],
            'delay_duration'           => $plan['delay_duration'],
            'per_installment_late_fee' => $perInstallmentLateFee,
        ]);
    }

    /**
     * Records the transaction made for the DPS installment or the maturity transfer.
     *
     * @param User $user
     * @param DepositPensionScheme $dps
     * @param string $reason
     * @return void
     */
    private function recordTransaction(User $user, DepositPensionScheme $dps, string $reason): void
    {
        $currency = bs('site_cur');

        if ($reason == 'installment_payment') {
            $formattedAmount = showAmount($dps->per_installment);

            $user->transactions()->create([
                'amount'       => $dps->per_installment,
                'post_balance' => $user->balance,
                'trx_type'     => '-',
                'trx'          => $dps->scheme_code,
                'details'      => "A payment of $formattedAmount $currency has been made for the DPS installment.",
                'remark'       => 'dps_installment',
            ]);
        } else {
            $transferableAmount = $dps->maturity_amount - $dps->total_late_fees;
            $formattedAmount    = showAmount($transferableAmount);

            $user->transactions()->create([
                'amount'       => $transferableAmount,
                'charge'       => $dps->total_late_fees,
                'post_balance' => $user->balance,
                'trx_type'     => '+',
                'trx'          => getTrx(),
                'details'      => "$formattedAmount $currency has been added to the balance upon DPS closure.",
                'remark'       => 'dps_maturity_amount_transfer',
            ]);
        }
    }

    /**
     * Sends a notification to the admin when a DPS is opened or closed.
     *
     * @param User $user
     * @param DepositPensionScheme $dps
     * @param string $title
     * @return void
     */
    private function notifyAdmin(User $user, DepositPensionScheme $dps, string $title): void
    {
        AdminNotification::create([
            'user_id'   => $user->id,
            'title'     => $title,
            'click_url' => urlPath('admin.dps.index') . "?search=$dps->scheme_code",
        ]);
    }

    /**
     * Sends a notification to the user based on the provided template.
     *
     * @param User $user
     * @param string $template
     * @param DepositPensionScheme $dps
     * @param Installment|null $installment
     * @return void
     */
    private function notifyUser(User $user, string $template, DepositPensionScheme $dps, ?Installment $installment = null): void
    {
        $data = [
            'scheme_code' => $dps->scheme_code,
        ];

        $templateData = [
            'DPS_OPEN'            => [
                'plan_name'             => $dps->plan_name,
                'per_installment'       => showAmount($dps->per_installment),
                'next_installment_date' => now()->addDays($dps->installment_interval)->toDateString(),
                'total_installment'     => $dps->total_installment,
            ],
            'DPS_INSTALLMENT_DUE' => [
                'installment_amount' => showAmount($dps->per_installment),
                'installment_date'   => $installment ? showDateTime($installment->installment_date, 'd M, Y') : null,
            ],
            'DPS_MATURE'          => [
                'plan_name'          => $dps->plan_name,
                'total_installments' => $dps->total_installment,
                'total_deposit'      => showAmount($dps->total_deposit_amount),
                'interest_earned'    => showAmount($dps->profit_amount),
                'maturity_amount'    => showAmount($dps->maturity_amount),
                'maturity_date'      => now()->toFormattedDateString(),
            ],
            'DPS_CLOSE'           => [
                'maturity_amount' => showAmount($dps->maturity_amount - $dps->total_late_fees),
            ]
        ];

        $notificationData = array_merge($data, $templateData[$template]);

        notify($user, $template, $notificationData);
    }

    /**
     * Processes a DPS installment, including payment handling and notifications.
     *
     * @param Installment $installment
     * @return void
     */
    public function processInstallment(Installment $installment): void
    {
        $dps  = $installment->installmentable;
        $user = $dps->user;

        if ($user->balance < $dps->per_installment) {
            $this->handleInsufficientBalance($user, $dps, $installment);
        } else {
            $this->processPayment($user, $dps, $installment);
        }
    }

    /**
     * Handles cases where the user has insufficient balance for the installment.
     *
     * @param User $user
     * @param DepositPensionScheme $dps
     * @param Installment $installment
     * @return void
     */
    private function handleInsufficientBalance(User $user, DepositPensionScheme $dps, Installment $installment): void
    {
        $lastNotifiedAt = $dps->late_fee_last_notified_at ?? now()->subHours(10);

        if ((int)$lastNotifiedAt->diffInHours(now()) >= 10) {
            $this->notifyUser($user, 'DPS_INSTALLMENT_DUE', $dps, $installment);

            $dps->update([
                'late_fee_last_notified_at' => now()
            ]);
        }
    }

    /**
     * Processes the DPS installment payment and updates records.
     *
     * @param User $user
     * @param DepositPensionScheme $dps
     * @param Installment $installment
     * @return void
     */
    private function processPayment(User $user, DepositPensionScheme $dps, Installment $installment): void
    {
        $overdueDays = (int)$installment->installment_date->diffInDays(today());

        if ($overdueDays >= $dps->delay_duration) {
            $overdueCharge = $dps->per_installment_late_fee * $overdueDays;

            $dps->increment('total_late_fees', $overdueCharge);
        }

        $user->decrement('balance', $dps->per_installment);

        $this->recordTransaction($user, $dps, 'installment_payment');

        $installment->update([
            'given_at' => now()
        ]);

        $dps->increment('given_installment');

        if ($dps->total_installment == $dps->given_installment) {
            $dps->update([
                'status' => ManageStatus::DPS_MATURED,
            ]);

            $this->notifyUser($user, 'DPS_MATURE', $dps);
        }
    }

    /**
     * Fetches a paginated list of Deposit Pension Schemes (DPS) based on the given filter.
     *
     * @param string|null $filter
     * @return LengthAwarePaginatorContract
     */
    public function fetchDepositPensionSchemesByType(?string $filter = null): LengthAwarePaginatorContract
    {
        $query = DepositPensionScheme::query()->with('user')
            ->withCount(['installments as late_installments' => function (Builder $builder) {
                $builder->whereDate('installment_date', '<', today())
                    ->whereNull('given_at');
            }]);

        if ($filter) {
            if (in_array($filter, ['running', 'matured', 'closed'])) {
                $query->$filter();
            } else {
                $query->having('late_installments', '>', 0);
            }
        }

        $dpsList = $query->searchable(['scheme_code', 'user:firstname,lastname,account_number'])
            ->latest()
            ->paginate(getPaginate());

        $dpsList->getCollection()->transform(function (DepositPensionScheme $dps) {
            $dps->next_installment_date = $this->getNextInstallmentDate($dps);

            return $dps;
        });

        return $dpsList;
    }

    /**
     * Retrieves a paginated list of Deposit Pension Schemes (DPS) for a user.
     *
     * @param User $user
     * @return LengthAwarePaginatorContract
     */
    public function getDpsList(User $user): LengthAwarePaginatorContract
    {
        $dpsList = $user->depositPensionSchemes()
            ->searchable(['plan_name', 'scheme_code'])
            ->latest();

        if (request()->date) {
            $dateRange = explode('-', request()->date);
            $startDate = Carbon::parse(trim($dateRange[0]));
            $endDate   = isset($dateRange[1]) ? Carbon::parse(trim($dateRange[1])) : $startDate;

            // Filter using PHP collection after retrieving all DPS records
            $dpsList = $dpsList->get()->filter(function (DepositPensionScheme $dps) use ($startDate, $endDate) {
                $nextInstallmentDate = Carbon::parse($this->getNextInstallmentDate($dps));

                return $dps->status == ManageStatus::DPS_RUNNING && $nextInstallmentDate->betweenIncluded($startDate, $endDate);
            });

            // Convert collection to pagination manually
            $dpsList = new LengthAwarePaginator(
                $dpsList->forPage(request()->input('page', 1), getPaginate()),
                $dpsList->count(),
                getPaginate(),
                request()->input('page', 1),
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            $dpsList = $dpsList->paginate(getPaginate());
        }

        $dpsList->getCollection()->transform(function (DepositPensionScheme $dps) {
            $dps->next_installment_date = $this->getNextInstallmentDate($dps);

            return $dps;
        });

        return $dpsList;
    }

    /**
     * Handles the maturity transfer process for the Deposit Pension Scheme.
     *
     * @param DepositPensionScheme $dps
     * @param User $user
     * @return void
     */
    public function handleTransferMaturity(DepositPensionScheme $dps, User $user): void
    {
        $transferableAmount = $dps->maturity_amount - $dps->total_late_fees;

        $user->increment('balance', $transferableAmount);

        $this->recordTransaction($user, $dps, 'maturity_transfer');

        $dps->update([
            'transfer_at' => now(),
            'status'      => ManageStatus::DPS_CLOSED,
        ]);

        $this->notifyUser($user, 'DPS_CLOSE', $dps);

        $this->notifyAdmin($user, $dps, 'A deposit pension scheme (DPS) has been closed');
    }
}
