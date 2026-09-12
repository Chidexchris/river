<?php

namespace App\Services;

use App\Constants\ManageStatus;
use App\Models\AdminNotification;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\LoanPlan;
use App\Models\Setting;
use App\Models\User;
use App\Traits\Makeable;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class LoanService extends BaseService
{
    use Makeable;

    /**
     * Validates the transaction state based on OTP requirements and loan plan.
     *
     * @param Setting $settings
     * @param array $transactionStateInformation
     * @return void
     * @throws Exception
     */
    public function validateTransactionState(Setting $settings, array $transactionStateInformation): void
    {
        $this->checkTransactionState($settings, $transactionStateInformation, 'loan_plan', 'take a Loan');
    }

    /**
     * Handles the initiation process for a new loan request.
     *
     * @param array $transactionStateInformation
     * @return void
     * @throws Exception
     */
    public function initiateLoan(array $transactionStateInformation): void
    {
        $this->validateTransactionState(bs(), $transactionStateInformation);

        $loanPlan = $transactionStateInformation['loan_plan'];

        if (!LoanPlan::active()->find($loanPlan['id'])) {
            throw new Exception('The selected Loan Plan is either inactive or does not exist.');
        }

        $user = auth('web')->user();

        $loan = $this->createLoan($user, $transactionStateInformation);

        $this->notifyAdmin($user, $loan, 'A new loan request has been submitted');
    }

    /**
     * Creates a new loan record for the specified user based on the provided transaction information.
     *
     * @param User $user
     * @param array $transactionInfo
     * @return Loan
     * @throws Exception
     */
    private function createLoan(User $user, array $transactionInfo): Loan
    {
        $formData       = json_decode($transactionInfo['loan_form']['form_data']);
        $payload        = $this->processFormFieldData($formData, $transactionInfo['loan_form_data']);
        $loanPlan       = $transactionInfo['loan_plan'];
        $perInstallment = ($transactionInfo['loan_amount'] * $loanPlan['installment_rate']) / 100;
        $lateFee        = $loanPlan['fixed_charge'] + (($loanPlan['percentage_charge'] * $perInstallment) / 100);

        return $user->loans()->create([
            'plan_name'                => $loanPlan['name'],
            'scheme_code'              => getTrx(),
            'amount_requested'         => $transactionInfo['loan_amount'],
            'form_data'                => json_encode($payload),
            'per_installment'          => $perInstallment,
            'installment_interval'     => $loanPlan['installment_interval'],
            'total_installment'        => $loanPlan['total_installments'],
            'delay_duration'           => $loanPlan['delay_duration'],
            'per_installment_late_fee' => $lateFee,
        ]);
    }

    /**
     * Sends an administrative notification for a new loan request.
     *
     * @param User $user
     * @param Loan $loan
     * @param string $title
     * @return void
     */
    private function notifyAdmin(User $user, Loan $loan, string $title): void
    {
        AdminNotification::create([
            'user_id'   => $user->id,
            'title'     => $title,
            'click_url' => urlPath('admin.loan.index') . "?search=$loan->scheme_code",
        ]);
    }

    /**
     * Retrieves a paginated list of Loans for a user.
     *
     * @param User $user
     * @return LengthAwarePaginatorContract
     */
    public function getLoanList(User $user): LengthAwarePaginatorContract
    {
        $loanList = $user->loans()
            ->searchable(['plan_name', 'scheme_code'])
            ->latest();

        if (request()->date) {
            $dateRange = explode('-', request()->date);
            $startDate = Carbon::parse(trim($dateRange[0]));
            $endDate   = isset($dateRange[1]) ? Carbon::parse(trim($dateRange[1])) : $startDate;

            // Filter using PHP collection after retrieving all Loan records
            $loanList = $loanList->get()->filter(function (Loan $loan) use ($startDate, $endDate) {
                $nextInstallmentDate = Carbon::parse($this->getNextInstallmentDate($loan));

                return $loan->status == ManageStatus::LOAN_RUNNING && $nextInstallmentDate->betweenIncluded($startDate, $endDate);
            });

            // Convert collection to pagination manually
            $loanList = new LengthAwarePaginator(
                $loanList->forPage(request()->input('page', 1), getPaginate()),
                $loanList->count(),
                getPaginate(),
                request()->input('page', 1),
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            $loanList = $loanList->paginate(getPaginate());
        }

        $loanList->getCollection()->transform(function (Loan $loan) {
            $loan->payable_amount        = $loan->per_installment * $loan->total_installment;
            $loan->next_installment_date = $this->getNextInstallmentDate($loan);

            return $loan;
        });

        return $loanList;
    }

    /**
     * Fetches a paginated list of Loans based on the given filter.
     *
     * @param string|null $filter
     * @return LengthAwarePaginatorContract
     */
    public function fetchLoansByType(?string $filter = null): LengthAwarePaginatorContract
    {
        $query = Loan::query()->with('user')
            ->withCount(['installments as late_installments' => function (Builder $builder) {
                $builder->whereDate('installment_date', '<', today())
                    ->whereNull('given_at');
            }]);

        if ($filter) {
            if (in_array($filter, ['rejected', 'running', 'paid', 'pending'])) {
                $query->$filter();
            } else {
                $query->having('late_installments', '>', 0);
            }
        }

        $loanList = $query->searchable(['scheme_code', 'user:firstname,lastname,account_number'])
            ->latest()
            ->paginate(getPaginate());

        $loanList->getCollection()->transform(function (Loan $loan) {
            $loan->payable_amount        = $loan->per_installment * $loan->total_installment;
            $loan->next_installment_date = $this->getNextInstallmentDate($loan);

            return $loan;
        });

        return $loanList;
    }

    /**
     * Sends a notification to the user based on the provided template.
     *
     * @param User $user
     * @param string $template
     * @param Loan $loan
     * @param Installment|null $installment
     * @return void
     */
    private function notifyUser(User $user, string $template, Loan $loan, ?Installment $installment = null): void
    {
        $data = [
            'scheme_code' => $loan->scheme_code,
        ];

        $templateData = [
            'LOAN_APPROVE'         => [
                'plan_name'                => $loan->plan_name,
                'amount'                   => showAmount($loan->amount_requested),
                'per_installment'          => showAmount($loan->per_installment),
                'installment_interval'     => $loan->installment_interval,
                'next_installment_date'    => now()->addDays($loan->installment_interval)->toFormattedDateString(),
                'total_installment'        => $loan->total_installment,
                'delay_duration'           => $loan->delay_duration . ' ' . Str::plural('Day', $loan->delay_duration),
                'per_installment_late_fee' => showAmount($loan->per_installment_late_fee),
            ],
            'LOAN_REJECT'          => [
                'plan_name' => $loan->plan_name,
                'amount'    => showAmount($loan->amount_requested),
                'reason'    => $loan->admin_feedback,
            ],
            'LOAN_INSTALLMENT_DUE' => [
                'installment_amount' => showAmount($loan->per_installment),
                'installment_date'   => $installment ? showDateTime($installment->installment_date, 'd M, Y') : null,
            ],
            'LOAN_PAID'            => [
                'plan_name'          => $loan->plan_name,
                'scheme_code'        => $loan->scheme_code,
                'total_installments' => $loan->total_installment,
                'amount'             => showAmount($loan->per_installment * $loan->total_installment),
                'charge'             => showAmount($loan->total_late_fees),
                'date'               => now()->toFormattedDateString(),
            ],
        ];

        $notificationData = array_merge($data, $templateData[$template]);

        notify($user, $template, $notificationData);
    }

    /**
     * Records the transaction made for the Loan amount disbursement or the Loan installment.
     *
     * @param User $user
     * @param Loan $loan
     * @param string $reason
     * @param float|null $charge
     * @return void
     */
    private function recordTransaction(User $user, Loan $loan, string $reason, ?float $charge = null): void
    {
        if ($reason == 'loan_amount_disbursement') {
            $user->transactions()->create([
                'amount'       => $loan->amount_requested,
                'post_balance' => $user->balance,
                'trx_type'     => '+',
                'trx'          => getTrx(),
                'details'      => 'Loan approved and disbursed',
                'remark'       => 'loan_taken'
            ]);
        } else {
            $formattedAmount = showAmount($loan->per_installment);
            $currency        = bs('site_cur');

            $user->transactions()->create([
                'amount'       => $loan->per_installment,
                'charge'       => $charge,
                'post_balance' => $user->balance,
                'trx_type'     => '-',
                'trx'          => $loan->scheme_code,
                'details'      => "A payment of $formattedAmount $currency has been made for the Loan installment.",
                'remark'       => 'loan_installment',
            ]);
        }
    }

    /**
     * Process the approval of a loan and perform necessary actions.
     *
     * @param Loan $loan
     * @param User $user
     * @return void
     */
    public function processLoanApproval(Loan $loan, User $user): void
    {
        $loan->update([
            'status'      => ManageStatus::LOAN_RUNNING,
            'approved_at' => now(),
        ]);

        $user->increment('balance', $loan->amount_requested);

        $this->recordTransaction($user, $loan, 'loan_amount_disbursement');

        $this->saveInstallments($loan);

        $this->notifyUser($user, 'LOAN_APPROVE', $loan);
    }

    /**
     * Process the rejection of a loan and perform necessary actions.
     *
     * @param Loan $loan
     * @param array $data
     * @return void
     */
    public function processLoanRejection(Loan $loan, array $data): void
    {
        $loan->update([
            'status'         => ManageStatus::LOAN_REJECTED,
            'admin_feedback' => $data['admin_feedback'],
        ]);

        $this->notifyUser($loan->user, 'LOAN_REJECT', $loan);
    }

    /**
     * Processes a Loan installment, including payment handling and notifications.
     *
     * @param Installment $installment
     * @return void
     */
    public function processInstallment(Installment $installment): void
    {
        $loan = $installment->installmentable;
        $user = $loan->user;

        if ($user->balance < $loan->per_installment) {
            $this->handleInsufficientBalance($user, $loan, $installment);
        } else {
            $this->processPayment($user, $loan, $installment);
        }
    }

    /**
     * Handles cases where the user has insufficient balance for the installment.
     *
     * @param User $user
     * @param Loan $loan
     * @param Installment $installment
     * @return void
     */
    private function handleInsufficientBalance(User $user, Loan $loan, Installment $installment): void
    {
        $lastNotifiedAt = $loan->late_fee_last_notified_at ?? now()->subHours(10);

        if ((int)$lastNotifiedAt->diffInHours(now()) >= 10) {
            $this->notifyUser($user, 'LOAN_INSTALLMENT_DUE', $loan, $installment);

            $loan->update([
                'late_fee_last_notified_at' => now()
            ]);
        }
    }

    /**
     * Processes the Loan installment payment and updates records.
     *
     * @param User $user
     * @param Loan $loan
     * @param Installment $installment
     * @return void
     */
    private function processPayment(User $user, Loan $loan, Installment $installment): void
    {
        $overdueDays   = (int)$installment->installment_date->diffInDays(today());
        $overdueCharge = 0.0;

        if ($overdueDays >= $loan->delay_duration) {
            $overdueCharge = $loan->per_installment_late_fee * $overdueDays;

            $loan->increment('total_late_fees', $overdueCharge);
        }

        $user->decrement('balance', ($loan->per_installment + $overdueCharge));

        $this->recordTransaction($user, $loan, 'installment_payment', $overdueCharge);

        $installment->update([
            'given_at' => now()
        ]);

        $loan->increment('given_installment');

        if ($loan->total_installment == $loan->given_installment) {
            $loan->update([
                'status' => ManageStatus::LOAN_PAID,
            ]);

            $this->notifyUser($user, 'LOAN_PAID', $loan);
        }
    }
}
