<?php

namespace App\Http\Controllers\Admin;

use App\Models\Deposit;
use App\Models\Gateway;
use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;

class DepositController extends Controller
{
    function index()
    {
        $this->authorize('viewAny', Deposit::class);

        $pageTitle   = 'All Deposits';
        $depositData = $this->depositData(null, true);
        $deposits    = $depositData['data'];
        $summary     = $depositData['summary'];
        $done        = $summary['done'];
        $pending     = $summary['pending'];
        $cancelled   = $summary['cancelled'];
        $charge      = $summary['charge'];

        return view('admin.page.deposits', compact('pageTitle', 'deposits', 'done', 'pending', 'cancelled', 'charge'));
    }

    function pending()
    {
        $this->authorize('viewPendingDeposits', Deposit::class);

        $pageTitle = 'Pending Deposits';
        $deposits  = $this->depositData('pending');

        return view('admin.page.deposits', compact('pageTitle', 'deposits'));
    }

    function done()
    {
        $this->authorize('viewDoneDeposits', Deposit::class);

        $pageTitle = 'Done Deposits';
        $deposits  = $this->depositData('done');

        return view('admin.page.deposits', compact('pageTitle', 'deposits'));
    }

    function cancelled()
    {
        $this->authorize('viewCancelledDeposits', Deposit::class);

        $pageTitle = 'Cancelled Deposits';
        $deposits  = $this->depositData('cancelled');

        return view('admin.page.deposits', compact('pageTitle', 'deposits'));
    }

    protected function depositData($scope = null, $summary = false)
    {
        if ($scope) {
            $deposits = Deposit::with(['gateway', 'user', 'branch', 'staff'])->$scope();
        } else {
            $deposits = Deposit::with(['gateway', 'user', 'branch', 'staff'])->index();
        }

        $deposits = $deposits->searchable(['trx', 'user:username'])->dateFilter();

        // Filter by payment method
        if (request('method')) {
            $method   = Gateway::where('alias', request('method'))->firstOrFail();
            $deposits = $deposits->where('method_code', $method->code);
        }

        if (!$summary) {
            return $deposits->latest()->paginate(getPaginate());
        } else {
            $doneSummary      = (clone $deposits)->done()->sum('amount');
            $pendingSummary   = (clone $deposits)->pending()->sum('amount');
            $cancelledSummary = (clone $deposits)->cancelled()->sum('amount');
            $chargeSummary    = (clone $deposits)->done()->sum('charge');

            return [
                'data'    => $deposits->latest()->paginate(getPaginate()),
                'summary' => [
                    'done'      => $doneSummary,
                    'pending'   => $pendingSummary,
                    'cancelled' => $cancelledSummary,
                    'charge'    => $chargeSummary,
                ],
            ];
        }
    }

    function approve(int $id)
    {
        $deposit = Deposit::where('id', $id)->pending()->firstOrFail();

        $this->authorize('approve', $deposit);

        PaymentController::dataUpdate($deposit, true);

        $toast[] = ['success', 'Deposit successfully approved'];

        return back()->with('toasts', $toast);
    }

    function reject(int $id)
    {
        $this->validate(request(), [
            'admin_feedback' => 'required|max:255',
        ]);

        $deposit = Deposit::with('user')->where('id', $id)->pending()->firstOrFail();

        $this->authorize('reject', $deposit);

        $deposit->status         = ManageStatus::PAYMENT_CANCEL;
        $deposit->admin_feedback = request('admin_feedback');
        $deposit->save();

        notify($deposit->user, 'DEPOSIT_REJECT', [
            'method_name'       => $deposit->gatewayCurrency()->name,
            'method_amount'     => showAmount($deposit->final_amount),
            'method_currency'   => $deposit->method_currency,
            'amount'            => showAmount($deposit->amount),
            'charge'            => showAmount($deposit->charge),
            'rate'              => showAmount($deposit->rate),
            'trx'               => $deposit->trx,
            'rejection_message' => $deposit->admin_feedback,
        ]);

        $toast[] = ['success', 'Deposit successfully rejected'];

        return back()->with('toasts', $toast);
    }
}
