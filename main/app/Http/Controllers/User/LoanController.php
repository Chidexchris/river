<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanRequest;
use App\Models\Loan;
use App\Models\LoanPlan;
use App\Services\LoanService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoanController extends Controller
{
    public function plans()
    {
        $pageTitle = 'Loan Plans';
        $user      = auth('web')->user();
        $loanPlans = LoanPlan::active()->get();

        return view("{$this->activeTheme}user.loan.plans", compact('pageTitle', 'user', 'loanPlans'));
    }

    public function choosePlan(Request $request)
    {
        $validated = $request->validate([
            'loan_plan'   => 'required|integer',
            'loan_amount' => 'required|numeric|gt:0',
        ]);

        $plan = LoanPlan::with('form')->active()->findOrFail($validated['loan_plan']);

        if ($validated['loan_amount'] < $plan->minimum_amount || $validated['loan_amount'] > $plan->maximum_amount) {
            $currency  = bs('site_cur');
            $minAmount = showAmount($plan->minimum_amount);
            $maxAmount = showAmount($plan->maximum_amount);

            return back()->with('toasts', [
                ['error', "The amount you entered must be between $minAmount $currency and $maxAmount $currency."]
            ]);
        }

        $transactionStateInformation = [
            'loan_plan'   => collect($plan->getAttributes())
                ->except(['icon', 'instruction', 'status', 'created_at', 'updated_at'])
                ->toArray(),
            'loan_form'   => collect($plan->form->getAttributes())
                ->except(['act', 'created_at', 'updated_at'])
                ->toArray(),
            'loan_amount' => $validated['loan_amount'],
        ];

        session()->put('transaction_state_information', $transactionStateInformation);

        return to_route('user.loan.plan.preview', $plan);
    }

    public function previewPlan(LoanPlan $plan)
    {
        $transactionStateInformation = session('transaction_state_information', []);

        if (
            empty($transactionStateInformation) ||
            !array_key_exists('loan_plan', $transactionStateInformation) ||
            $transactionStateInformation['loan_plan']['id'] !== $plan->id
        ) {
            return to_route('user.loan.plans');
        }

        $pageTitle              = 'Plan Preview';
        $user                   = auth('web')->user();
        $loanAmount             = $transactionStateInformation['loan_amount'];
        $perInstallment         = ($loanAmount * $plan->installment_rate) / 100;
        $totalInstallmentAmount = $perInstallment * $plan->total_installments;
        $lateFee                = $plan->fixed_charge + (($plan->percentage_charge * $perInstallment) / 100);

        return view("{$this->activeTheme}user.loan.preview", compact('pageTitle', 'user', 'plan', 'loanAmount', 'perInstallment', 'totalInstallmentAmount', 'lateFee'));
    }

    public function confirmPlan(LoanRequest $request)
    {
        $validated                   = $request->validated();
        $transactionStateInformation = session('transaction_state_information');
        $formData                    = json_decode($transactionStateInformation['loan_form']['form_data']);

        // Store file temporarily if uploaded
        foreach ($formData as $field) {
            if ($field->type == 'file' && $request->hasFile($field->label)) {
                $uploadedFile             = $request->file($field->label);
                $fileName                 = uniqid() . time() . '.' . $uploadedFile->getClientOriginalExtension();
                $validated[$field->label] = $uploadedFile->storeAs('temp_uploads', $fileName);
            }
        }

        $transactionStateInformation['loan_form_data'] = array_diff_key($validated, array_flip(['authorization_mode']));

        $settings = bs();
        $user     = auth('web')->user();

        // Check if OTP is required
        if ($settings->email_based_otp || $settings->sms_based_otp) {
            LoanService::make()->processOTP(
                $validated['authorization_mode'],
                $user,
                $transactionStateInformation,
                'user.loan.plan.finalize'
            );

            return to_route('user.otp.form');
        }

        session()->put('transaction_state_information', $transactionStateInformation);

        return to_route('user.loan.plan.finalize');
    }

    public function finalizePlan()
    {
        $transactionStateInformation = session('transaction_state_information', []);

        try {
            LoanService::make()->initiateLoan($transactionStateInformation);

            session()->forget('transaction_state_information');

            return to_route('user.loan.list')->with('toasts', [
                ['success', 'Your loan application has been successfully submitted.']
            ]);
        } catch (Exception $exception) {
            return to_route('user.loan.plans')->with('toasts', [
                ['error', $exception->getMessage()],
            ]);
        }
    }

    public function list()
    {
        $pageTitle = 'My Loan List';
        $user      = auth('web')->user();
        $loanList  = LoanService::make()->getLoanList($user);

        return view("{$this->activeTheme}user.loan.list", compact('pageTitle', 'user', 'loanList'));
    }

    public function installments(Loan $loan)
    {
        $user = auth('web')->user();

        if ($loan->user()->isNot($user)) abort(Response::HTTP_FORBIDDEN);

        $pageTitle    = 'Loan Installments';
        $installments = LoanService::make()->transformInstallment(
            $loan->installments()->paginate(getPaginate())
        );

        return view("{$this->activeTheme}user.loan.installments", compact('pageTitle', 'user', 'loan', 'installments'));
    }
}
