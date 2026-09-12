<?php

namespace App\Policies;

use App\Constants\ManageStatus;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\User;

class UserPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all users');
    }

    public function viewActiveUsers(Admin $admin): bool
    {
        return $admin->can('view active users');
    }

    public function viewBannedUsers(Admin $admin): bool
    {
        return $admin->can('view banned users');
    }

    public function viewKYCPendingUsers(Admin $admin): bool
    {
        return $admin->can('view kyc pending users');
    }

    public function viewKYCUnconfirmedUsers(Admin $admin): bool
    {
        return $admin->can('view kyc unconfirmed users');
    }

    public function viewEmailUnconfirmedUsers(Admin $admin): bool
    {
        return $admin->can('view email unconfirmed users');
    }

    public function viewMobileUnconfirmedUsers(Admin $admin): bool
    {
        return $admin->can('view mobile unconfirmed users');
    }

    public function viewUserDetails(Admin $admin): bool
    {
        return $admin->can('view user details');
    }

    public function updateUserData(Admin $admin): bool
    {
        return $admin->can('update user information');
    }

    public function loginAsUser(Admin $admin): bool
    {
        return $admin->can('login as user');
    }

    public function updateUserBalance(Admin $admin): bool
    {
        return $admin->can('update user balance');
    }

    public function changeUserStatus(Admin $admin): bool
    {
        return $admin->can('change user status');
    }

    public function approveKYCApplication(Admin $admin): bool
    {
        return $admin->can('approve kyc application');
    }

    public function rejectKYCApplication(Admin $admin): bool
    {
        return $admin->can('reject kyc application');
    }

    public function createBankAccount(Staff $staff): bool
    {
        return $staff->designation == ManageStatus::BRANCH_ACCOUNT_OFFICER;
    }

    public function editBankAccount(Staff $staff, User $user): bool
    {
        return $staff->designation == ManageStatus::BRANCH_ACCOUNT_OFFICER && $staff->id == $user->staff_id;
    }

    public function fetchAccountStatement(Staff $staff, User $user): bool
    {
        return $staff->designation == ManageStatus::BRANCH_ACCOUNT_OFFICER && $staff->id == $user->staff_id;
    }
}
