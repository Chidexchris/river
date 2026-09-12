<?php

namespace App\Policies;

use App\Models\Admin;

class LoanPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all loans');
    }

    public function viewPendingLoans(Admin $admin): bool
    {
        return $admin->can('view pending loans');
    }

    public function viewRunningLoans(Admin $admin): bool
    {
        return $admin->can('view running loans');
    }

    public function viewLateInstallmentLoans(Admin $admin): bool
    {
        return $admin->can('view late installment loans');
    }

    public function viewPaidLoans(Admin $admin): bool
    {
        return $admin->can('view paid loans');
    }

    public function viewRejectedLoans(Admin $admin): bool
    {
        return $admin->can('view rejected loans');
    }

    public function downloadFile(Admin $admin): bool
    {
        return $admin->can('download applicant file');
    }

    public function markAsApprove(Admin $admin): bool
    {
        return $admin->can('approve loan');
    }

    public function markAsReject(Admin $admin): bool
    {
        return $admin->can('reject loan');
    }

    public function viewLoanInstallments(Admin $admin): bool
    {
        return $admin->can('view loan installments');
    }
}
