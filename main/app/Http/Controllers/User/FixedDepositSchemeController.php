<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\FixedDepositScheme;
use App\Models\FixedDepositSchemePlan;
use App\Models\User;
use App\Services\FixedDepositSchemeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FixedDepositSchemeController extends Controller
{
    public function plans()
    {
        $pageTitle = 'FDS Plans';
        $user      = auth('web')->user();
        $fdsPlans  = FixedDepositSchemePlan::active()->get();

        return view("{$this->activeTheme}user.fds.plans", compact('pageTitle', 'user', 'fdsPlans'));
    }

    public function choosePlan(Request $request)
    {
        $validated = $request->validate([
            'fds_plan'   => 'required|integer',
            'fds_amount' => 'required|numeric|gt:0',
        ]);

        $plan = FixedDepositSchemePlan::active()->findOrFail($validated['fds_plan']);

        if ($validated['fds_amount'] < $plan->minimum_amount || $validated['fds_amount'] > $plan->maximum_amount) {
            $currency  = bs('site_cur');
            $minAmount = showAmount($plan->minimum_amount);
            $maxAmount = showAmount($plan->maximum_amount);

            return back()->with('toasts', [
                ['error', "The amount you entered must be between $minAmount $currency and $maxAmount $currency."]
            ]);
        }

        $transactionStateInformation = [
            'fds_plan'      => collect($plan->getAttributes())
                ->except(['icon', 'status', 'created_at', 'updated_at'])
                ->toArray(),
            'invest_amount' => $validated['fds_amount'],
        ];

        session()->put('transaction_state_information', $transactionStateInformation);

        return to_route('user.fds.plan.preview', $plan);
    }

    public function previewPlan(FixedDepositSchemePlan $plan)
    {
        $transactionStateInformation = session('transaction_state_information', []);

        if (
            empty($transactionStateInformation) ||
            !array_key_exists('fds_plan', $transactionStateInformation) ||
            $transactionStateInformation['fds_plan']['id'] !== $plan->id
        ) {
            return to_route('user.fds.plans');
        }

        $pageTitle        = 'Plan Preview';
        $user             = auth('web')->user();
        $investAmount     = $transactionStateInformation['invest_amount'];
        $profitableAmount = ($investAmount * $plan->interest_rate) / 100;
        $lockedPeriod     = today()->addDays($plan->lock_in_period);

        return view("{$this->activeTheme}user.fds.preview", compact('pageTitle', 'user', 'plan', 'investAmount', 'profitableAmount', 'lockedPeriod'));
    }

    public function confirmPlan(Request $request)
    {
        $settings = bs();

        $validated = $request->validate([
            'authorization_mode' => [
                Rule::requiredIf(fn() => $settings->sms_based_otp || $settings->email_based_otp),
                'integer',
                Rule::in([ManageStatus::AUTHORIZATION_MODE_EMAIL, ManageStatus::AUTHORIZATION_MODE_SMS]),
            ],
        ]);

        $transactionStateInformation = session('transaction_state_information', []);

        if (!isset($transactionStateInformation['fds_plan'])) {
            return to_route('user.fds.plans')->with('toasts', [
                ['error', 'Please select a valid fixed deposit plan.']
            ]);
        }

        $user = auth('web')->user();

        if ($user->balance < $transactionStateInformation['invest_amount']) {
            return back()->with('toasts', [
                ['error', 'Insufficient balance to open the fixed deposit.']
            ]);
        }

        try {
            // Check if OTP is required
            if ($settings->email_based_otp || $settings->sms_based_otp) {
                FixedDepositSchemeService::make()->processOTP(
                    $validated['authorization_mode'],
                    $user,
                    $transactionStateInformation,
                    'user.fds.plan.finalize'
                );

                return to_route('user.otp.form');
            }

            return to_route('user.fds.plan.finalize');
        } catch (Exception $exception) {
            return to_route('user.fds.plans')->with('toasts', [
                ['error', $exception->getMessage()],
            ]);
        }
    }

    public function finalizePlan()
    {
        $transactionStateInformation = session('transaction_state_information', []);

        try {
            DB::transaction(function () use ($transactionStateInformation) {
                $user = User::lockForUpdate()->findOrFail(auth('web')->id());

                FixedDepositSchemeService::make()->openFds($user, $transactionStateInformation);

                session()->forget('transaction_state_information');
            });

            return to_route('user.fds.list')->with('toasts', [
                ['success', 'Your FDS has been successfully opened.']
            ]);
        } catch (Throwable $exception) {
            return to_route('user.fds.plans')->with('toasts', [
                ['error', $exception->getMessage()],
            ]);
        }
    }

    public function list()
    {
        $pageTitle = 'My FDS List';
        $user      = auth('web')->user();
        $fdsList   = $user->fixedDepositSchemes()
            ->searchable(['plan_name', 'scheme_code'])
            ->dateFilter()
            ->latest()
            ->paginate(getPaginate());

        return view("{$this->activeTheme}user.fds.list", compact('pageTitle', 'user', 'fdsList'));
    }

    public function installments(FixedDepositScheme $fds)
    {
        $user = auth('web')->user();

        if ($fds->user()->isNot($user)) abort(Response::HTTP_FORBIDDEN);

        $pageTitle    = 'FDS Installments';
        $installments = $fds->installments()->paginate(getPaginate());

        return view("{$this->activeTheme}user.fds.installments", compact('pageTitle', 'user', 'installments', 'fds'));
    }

    public function closeFds(FixedDepositScheme $fds)
    {
        $user = auth('web')->user();

        if ($fds->user()->isNot($user)) abort(Response::HTTP_FORBIDDEN);

        if ($fds->locked_until->endOfDay()->gt(now())) {
            return back()->with('toasts', [
                ['error', "This FDS is still locked and cannot be closed until {$fds->locked_until->toFormattedDateString()}."],
            ]);
        }

        if ($fds->status != ManageStatus::FDS_RUNNING) {
            return back()->with('toasts', [
                ['error', 'This FDS has already been closed.'],
            ]);
        }

        try {
            DB::transaction(function () use ($user, $fds) {
                FixedDepositSchemeService::make()->handleTransferProfit($user, $fds);
            });

            return back()->with('toasts', [
                ['success', 'The FDS has been successfully closed.'],
            ]);
        } catch (Throwable $exception) {
            return back()->with('toasts', [
                ['error', $exception->getMessage()],
            ]);
        }
    }
}
