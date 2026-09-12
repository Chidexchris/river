<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FixedDepositScheme;
use App\Services\FixedDepositSchemeService;

class FixedDepositSchemeController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', FixedDepositScheme::class);

        $pageTitle = 'All Fixed Deposit Schemes';
        $fdsList   = FixedDepositSchemeService::make()->fetchFixedDepositSchemesByType();

        return view('admin.fds.list', compact('pageTitle', 'fdsList'));
    }

    public function running()
    {
        $this->authorize('viewRunningFds', FixedDepositScheme::class);

        $pageTitle = 'Running Fixed Deposit Schemes';
        $fdsList   = FixedDepositSchemeService::make()->fetchFixedDepositSchemesByType('running');

        return view('admin.fds.list', compact('pageTitle', 'fdsList'));
    }

    public function closed()
    {
        $this->authorize('viewClosedFds', FixedDepositScheme::class);

        $pageTitle = 'Closed Fixed Deposit Schemes';
        $fdsList   = FixedDepositSchemeService::make()->fetchFixedDepositSchemesByType('closed');

        return view('admin.fds.list', compact('pageTitle', 'fdsList'));
    }

    public function installments(FixedDepositScheme $fds)
    {
        $this->authorize('viewFdsInstallments', $fds);

        $pageTitle    = 'FDS Installments';
        $installments = $fds->installments()->paginate(getPaginate());

        $fds->load('user:id,account_number');

        return view('admin.fds.installments', compact('pageTitle', 'fds', 'installments'));
    }
}
