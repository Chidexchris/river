<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Withdrawal;

class WithdrawController extends Controller
{
    function index()
    {
        $this->authorize('viewAny', Withdrawal::class);

        $pageTitle      = 'All Withdrawals';
        $withdrawalData = $this->withdrawalData('index', true);
        $withdrawals    = $withdrawalData['data'];
        $summary        = $withdrawalData['summary'];
        $done           = $summary['done'];
        $pending        = $summary['pending'];
        $cancelled      = $summary['cancelled'];
        $charge         = $summary['charge'];

        return view('admin.page.withdrawals', compact('pageTitle', 'withdrawals', 'done', 'pending', 'cancelled', 'charge'));
    }

    function pending()
    {
        $this->authorize('viewPendingWithdrawals', Withdrawal::class);

        $pageTitle   = 'Pending Withdrawals';
        $withdrawals = $this->withdrawalData('pending');

        return view('admin.page.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    function done()
    {
        $this->authorize('viewDoneWithdrawals', Withdrawal::class);

        $pageTitle   = 'Done Withdrawals';
        $withdrawals = $this->withdrawalData('done');

        return view('admin.page.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    function cancelled()
    {
        $this->authorize('viewCancelledWithdrawals', Withdrawal::class);

        $pageTitle   = 'Cancelled Withdrawals';
        $withdrawals = $this->withdrawalData('cancelled');

        return view('admin.page.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    protected function withdrawalData($scope = null, $summary = false)
    {
        if ($scope) {
            $withdrawals = Withdrawal::with(['user', 'method', 'branch', 'staff'])->$scope();
        } else {
            $withdrawals = Withdrawal::with(['user', 'method', 'branch', 'staff'])->index();
        }

        $withdrawals = $withdrawals->searchable(['trx', 'user:username'])->dateFilter();

        // Filter by payment method
        if (request('method')) {
            $withdrawals = $withdrawals->where('method_id', request('method'));
        }

        if (!$summary) {
            return $withdrawals->latest()->paginate(getPaginate());
        } else {
            $doneSummary      = (clone $withdrawals)->done()->sum('amount');
            $pendingSummary   = (clone $withdrawals)->pending()->sum('amount');
            $cancelledSummary = (clone $withdrawals)->cancelled()->sum('amount');
            $chargeSummary    = (clone $withdrawals)->done()->sum('charge');

            return [
                'data'    => $withdrawals->latest()->paginate(getPaginate()),
                'summary' => [
                    'done'      => $doneSummary,
                    'pending'   => $pendingSummary,
                    'cancelled' => $cancelledSummary,
                    'charge'    => $chargeSummary,
                ]
            ];
        }
    }

    function approve()
    {
        $this->validate(request(), [
            'id' => 'required|int|gt:0',
        ]);

        $withdraw = Withdrawal::with('user')->where('id', request('id'))->pending()->firstOrFail();

        $this->authorize('approve', $withdraw);

        $withdraw->status         = ManageStatus::PAYMENT_SUCCESS;
        $withdraw->admin_feedback = request('admin_feedback');
        $withdraw->save();

        notify($withdraw->user, 'WITHDRAW_APPROVE', [
            'method_name'     => $withdraw->method->name,
            'method_amount'   => showAmount($withdraw->final_amount),
            'method_currency' => $withdraw->currency,
            'amount'          => showAmount($withdraw->amount),
            'charge'          => showAmount($withdraw->charge),
            'rate'            => showAmount($withdraw->rate),
            'trx'             => $withdraw->trx,
            'admin_details'   => request('admin_feedback'),
            'post_balance'    => showAmount($withdraw->user->balance),
        ]);

        $toast[] = ['success', 'Withdrawal successfully approved'];

        return back()->with('toasts', $toast);
    }

    function reject()
    {
        $this->validate(request(), [
            'id'             => 'required|int|gt:0',
            'admin_feedback' => 'required|max:255',
        ]);

        $withdraw = Withdrawal::with('user')->where('id', request('id'))->pending()->firstOrFail();

        $this->authorize('reject', $withdraw);

        $withdraw->status         = ManageStatus::PAYMENT_CANCEL;
        $withdraw->admin_feedback = request('admin_feedback');
        $withdraw->save();

        $user          = $withdraw->user;
        $user->balance += $withdraw->amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $withdraw->user_id;
        $transaction->amount       = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->details      = showAmount($withdraw->amount) . ' ' . bs('site_cur') . ' refunded for withdrawal cancellation';
        $transaction->trx          = $withdraw->trx;
        $transaction->remark       = 'withdraw_reject';
        $transaction->save();

        notify($user, 'WITHDRAW_REJECT', [
            'method_name'     => $withdraw->method->name,
            'method_amount'   => showAmount($withdraw->final_amount),
            'method_currency' => $withdraw->currency,
            'amount'          => showAmount($withdraw->amount),
            'charge'          => showAmount($withdraw->charge),
            'rate'            => showAmount($withdraw->rate),
            'trx'             => $withdraw->trx,
            'post_balance'    => showAmount($user->balance),
            'admin_details'   => request('admin_feedback'),
        ]);

        $toast[] = ['success', 'Withdrawal successfully rejected'];

        return back()->with('toasts', $toast);
    }
}
