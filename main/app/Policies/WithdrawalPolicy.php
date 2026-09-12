<?php

namespace App\Policies;

use App\Constants\ManageStatus;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\User;

class WithdrawalPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all withdrawals');
    }

    public function viewPendingWithdrawals(Admin $admin): bool
    {
        return $admin->can('view pending withdrawals');
    }

    public function viewDoneWithdrawals(Admin $admin): bool
    {
        return $admin->can('view done withdrawals');
    }

    public function viewCancelledWithdrawals(Admin $admin): bool
    {
        return $admin->can('view cancelled withdrawals');
    }

    public function approve(Admin $admin): bool
    {
        return $admin->can('approve withdraw');
    }

    public function reject(Admin $admin): bool
    {
        return $admin->can('reject withdraw');
    }

    public function canWithdrawFromUserAccount(Staff $staff, User $user): bool
    {
        return $staff->designation == ManageStatus::BRANCH_ACCOUNT_OFFICER && $staff->id == $user->staff_id;
    }
}
