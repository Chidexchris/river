<?php

namespace App\Http\Controllers\User;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\DepositPensionScheme;
use App\Models\DepositPensionSchemePlan;
use App\Models\User;
use App\Services\DepositPensionSchemeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DepositPensionSchemeController extends Controller
{
    public function plans()
    {
        $pageTitle = 'DPS Plans';
        $user      = auth('web')->user();
        $dpsPlans  = DepositPensionSchemePlan::active()->get();

        return view("{$this->activeTheme}user.dps.plans", compact('pageTitle', 'user', 'dpsPlans'));
    }

    public function previewPlan(DepositPensionSchemePlan $plan)
    {
        $pageTitle = 'Plan Preview';
        $user      = auth('web')->user();

        return view("{$this->activeTheme}user.dps.preview", compact('pageTitle', 'user', 'plan'));
    }

    public function confirmPlan(Request $request)
    {
        $settings = bs();

        $validated = $request->validate([
            'dps_plan'           => 'required|integer',
            'authorization_mode' => [
                Rule::requiredIf(fn() => $settings->sms_based_otp || $settings->email_based_otp),
                'integer',
                Rule::in([ManageStatus::AUTHORIZATION_MODE_EMAIL, ManageStatus::AUTHORIZATION_MODE_SMS]),
            ],
        ]);

        $plan = DepositPensionSchemePlan::active()->findOrFail($validated['dps_plan']);
        $user = auth('web')->user();

        if ($user->balance < $plan->per_installment) {
            return back()->with('toasts', [
                ['error', 'You must have at least one installment amount available in your account.']
            ]);
        }

        $transactionStateInformation['dps_plan'] = collect($plan->getAttributes())
            ->except(['icon', 'status', 'created_at', 'updated_at'])
            ->toArray();

        try {
            // Check if OTP is required
            if ($settings->email_based_otp || $settings->sms_based_otp) {
                DepositPensionSchemeService::make()->processOTP(
                    $validated['authorization_mode'],
                    $user,
                    $transactionStateInformation,
                    'user.dps.plan.finalize'
                );

                return to_route('user.otp.form');
            }

            session()->put('transaction_state_information', $transactionStateInformation);

            return to_route('user.dps.plan.finalize');
        } catch (Exception $exception) {
            return to_route('user.dps.plans')->with('toasts', [
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

                DepositPensionSchemeService::make()->openDps($user, $transactionStateInformation);

                session()->forget('transaction_state_information');
            });

            return to_route('user.dps.list')->with('toasts', [
                ['success', 'Your DPS has been successfully opened.']
            ]);
        } catch (Throwable $exception) {
            return to_route('user.dps.plans')->with('toasts', [
                ['error', $exception->getMessage()],
            ]);
        }
    }

    public function list()
    {
        $pageTitle = 'My DPS List';
        $user      = auth('web')->user();
        $dpsList   = DepositPensionSchemeService::make()->getDpsList($user);

        return view("{$this->activeTheme}user.dps.list", compact('pageTitle', 'user', 'dpsList'));
    }

    public function installments(DepositPensionScheme $dps)
    {
        $user = auth('web')->user();

        if ($dps->user()->isNot($user)) abort(Response::HTTP_FORBIDDEN);

        $pageTitle    = 'DPS Installments';
        $installments = DepositPensionSchemeService::make()->transformInstallment(
            $dps->installments()->paginate(getPaginate())
        );

        return view("{$this->activeTheme}user.dps.installments", compact('pageTitle', 'user', 'dps', 'installments'));
    }

    public function closeDps(DepositPensionScheme $dps)
    {
        $user = auth('web')->user();

        if ($dps->user()->isNot($user)) abort(Response::HTTP_FORBIDDEN);

        if ($dps->status != ManageStatus::DPS_MATURED) {
            return back()->with('toasts', [
                ['error', 'The DPS is not currently matured.'],
            ]);
        }

        try {
            DB::transaction(function () use ($dps, $user) {
                DepositPensionSchemeService::make()->handleTransferMaturity($dps, $user);
            });

            return back()->with('toasts', [
                ['success', 'The DPS has been successfully closed.'],
            ]);
        } catch (Throwable $exception) {
            return back()->with('toasts', [
                ['error', $exception->getMessage()],
            ]);
        }
    }
}
