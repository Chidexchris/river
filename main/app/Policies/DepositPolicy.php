<?php

namespace App\Policies;

use App\Constants\ManageStatus;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\User;

class DepositPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all deposits');
    }

    public function viewPendingDeposits(Admin $admin): bool
    {
        return $admin->can('view pending deposits');
    }

    public function viewDoneDeposits(Admin $admin): bool
    {
        return $admin->can('view done deposits');
    }

    public function viewCancelledDeposits(Admin $admin): bool
    {
        return $admin->can('view cancelled deposits');
    }

    public function approve(Admin $admin): bool
    {
        return $admin->can('approve deposit');
    }

    public function reject(Admin $admin): bool
    {
        return $admin->can('reject deposit');
    }

    public function canDepositToUserAccount(Staff $staff, User $user): bool
    {
        return $staff->designation == ManageStatus::BRANCH_ACCOUNT_OFFICER && $staff->id == $user->staff_id;
    }
}
