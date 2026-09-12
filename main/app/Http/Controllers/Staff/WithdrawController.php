<?php

namespace App\Http\Controllers\Staff;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class WithdrawController extends Controller
{
    public function index()
    {
        $staff = auth('staff')->user();

        $staff->load('branches');

        $branches  = $staff->branches;
        $branch    = session()->has('branchId') ? $branches->find(session('branchId')) : $branches->first();
        $pageTitle = "Withdrawals Made at the $branch->name";

        $withdrawals = Withdrawal::with('user')
            ->where('branch_id', $branch->id)
            ->searchable(['user:account_number', 'staff:name', 'trx'])
            ->dateFilter()
            ->done()
            ->latest()
            ->paginate(getPaginate());

        if (isManager()) $withdrawals->load('staff');

        return view('staff.page.withdrawals', compact('staff', 'pageTitle', 'withdrawals'));
    }

    public function store(Request $request, string $account)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|gt:0',
        ]);

        $amount = $validated['amount'];
        $user   = User::where('account_number', $account)->active()->firstOrFail();

        if ($amount > $user->balance) {
            $toast[] = ['error', 'The user does not have sufficient balance!'];

            return back()->with('toasts', $toast);
        }

        $staff = auth('staff')->user();

        if (Gate::forUser($staff)->denies('canWithdrawFromUserAccount', [Withdrawal::class, $user])) abort(Response::HTTP_FORBIDDEN);

        $staff->load('branches');

        $branch = $staff->branches->first();
        $trx    = getTrx();

        // decrement user balance
        $user->decrement('balance', $amount);

        // create withdraw
        $staff->withdrawals()->create([
            'user_id'      => $user->id,
            'branch_id'    => $branch->id,
            'amount'       => $amount,
            'currency'     => bs('site_cur'),
            'rate'         => 1,
            'trx'          => $trx,
            'final_amount' => $amount,
            'after_charge' => $amount,
            'status'       => ManageStatus::PAYMENT_SUCCESS
        ]);

        // create transaction
        $staff->transactions()->create([
            'user_id'      => $user->id,
            'branch_id'    => $branch->id,
            'amount'       => $amount,
            'post_balance' => $user->balance,
            'trx_type'     => '-',
            'trx'          => $trx,
            'details'      => "Withdrawn money from the $branch->name by Account Officer $staff->name",
            'remark'       => 'withdraw',
        ]);

        // notify user
        notify($user, 'WITHDRAW_FROM_BRANCH', [
            'account_number' => $user->account_number,
            'amount'         => showAmount($amount),
            'branch'         => $branch->name,
            'staff'          => $staff->name,
            'trx'            => $trx,
            'post_balance'   => showAmount($user->balance),
        ]);

        $toast[] = ['success', 'The amount has been withdrawn successfully'];

        return back()->with('toasts', $toast);
    }
}
