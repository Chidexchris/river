<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepositPensionScheme;
use App\Services\DepositPensionSchemeService;

class DepositPensionSchemeController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', DepositPensionScheme::class);

        $pageTitle = 'All Deposit Pension Schemes';
        $dpsList   = DepositPensionSchemeService::make()->fetchDepositPensionSchemesByType();

        return view('admin.dps.list', compact('pageTitle', 'dpsList'));
    }

    public function running()
    {
        $this->authorize('viewRunningDps', DepositPensionScheme::class);

        $pageTitle = 'Running Deposit Pension Schemes';
        $dpsList   = DepositPensionSchemeService::make()->fetchDepositPensionSchemesByType('running');

        return view('admin.dps.list', compact('pageTitle', 'dpsList'));
    }

    public function lateInstallment()
    {
        $this->authorize('viewLateInstallmentDps', DepositPensionScheme::class);

        $pageTitle = 'Late Installment Deposit Pension Schemes';
        $dpsList   = DepositPensionSchemeService::make()->fetchDepositPensionSchemesByType('late_installment');

        return view('admin.dps.list', compact('pageTitle', 'dpsList'));
    }

    public function matured()
    {
        $this->authorize('viewMaturedDps', DepositPensionScheme::class);

        $pageTitle = 'Matured Deposit Pension Schemes';
        $dpsList   = DepositPensionSchemeService::make()->fetchDepositPensionSchemesByType('matured');

        return view('admin.dps.list', compact('pageTitle', 'dpsList'));
    }

    public function closed()
    {
        $this->authorize('viewClosedDps', DepositPensionScheme::class);

        $pageTitle = 'Closed Deposit Pension Schemes';
        $dpsList   = DepositPensionSchemeService::make()->fetchDepositPensionSchemesByType('closed');

        return view('admin.dps.list', compact('pageTitle', 'dpsList'));
    }

    public function installments(DepositPensionScheme $dps)
    {
        $this->authorize('viewDpsInstallments', $dps);

        $pageTitle    = 'DPS Installments';
        $installments = DepositPensionSchemeService::make()->transformInstallment(
            $dps->installments()->paginate(getPaginate())
        );

        $dps->load('user:id,account_number');

        return view('admin.dps.installments', compact('pageTitle', 'dps', 'installments'));
    }
}
