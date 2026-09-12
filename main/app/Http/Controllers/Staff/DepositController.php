<?php

namespace App\Http\Controllers\Staff;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class DepositController extends Controller
{
    public function index()
    {
        $staff = auth('staff')->user();

        $staff->load('branches');

        $branches  = $staff->branches;
        $branch    = session()->has('branchId') ? $branches->find(session('branchId')) : $branches->first();
        $pageTitle = "Deposits Made at the $branch->name";

        $deposits = Deposit::with('user')
            ->where('branch_id', $branch->id)
            ->searchable(['user:account_number', 'staff:name', 'trx'])
            ->dateFilter()
            ->done()
            ->latest()
            ->paginate(getPaginate());

        if (isManager()) $deposits->load('staff');

        return view('staff.page.deposits', compact('staff', 'pageTitle', 'deposits'));
    }

    public function store(Request $request, string $account)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|gt:0',
        ]);

        $amount = $validated['amount'];

        $user  = User::where('account_number', $account)->active()->firstOrFail();
        $staff = auth('staff')->user();

        if (Gate::forUser($staff)->denies('canDepositToUserAccount', [Deposit::class, $user])) abort(Response::HTTP_FORBIDDEN);

        $staff->load('branches');

        $branch = $staff->branches->first();
        $trx    = getTrx();

        // increment user balance
        $user->increment('balance', $amount);

        // create deposit
        $staff->deposits()->create([
            'user_id'         => $user->id,
            'branch_id'       => $branch->id,
            'method_currency' => bs('site_cur'),
            'amount'          => $amount,
            'rate'            => 1,
            'final_amount'    => $amount,
            'trx'             => $trx,
            'status'          => ManageStatus::PAYMENT_SUCCESS
        ]);

        // create transaction
        $staff->transactions()->create([
            'user_id'      => $user->id,
            'branch_id'    => $branch->id,
            'amount'       => $amount,
            'post_balance' => $user->balance,
            'trx_type'     => '+',
            'trx'          => $trx,
            'details'      => "Deposited money from the $branch->name by Account Officer $staff->name",
            'remark'       => 'deposit',
        ]);

        // referral calculation
        if (bs('referral_system') && $user->referral_action_limit) {
            storeLevelWiseCommission($user, $amount, 'deposit_commission');
        }

        // notify user
        notify($user, 'DEPOSIT_FROM_BRANCH', [
            'account_number' => $user->account_number,
            'amount'         => showAmount($amount),
            'branch'         => $branch->name,
            'staff'          => $staff->name,
            'trx'            => $trx,
            'post_balance'   => showAmount($user->balance),
        ]);

        $toast[] = ['success', 'The amount has been deposited successfully'];

        return back()->with('toasts', $toast);
    }
}
