<?php

namespace App\Policies;

use App\Models\Admin;

class DepositPensionSchemePolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all dps');
    }

    public function viewRunningDps(Admin $admin): bool
    {
        return $admin->can('view running dps');
    }

    public function viewLateInstallmentDps(Admin $admin): bool
    {
        return $admin->can('view late installment dps');
    }

    public function viewMaturedDps(Admin $admin): bool
    {
        return $admin->can('view matured dps');
    }

    public function viewClosedDps(Admin $admin): bool
    {
        return $admin->can('view closed dps');
    }

    public function viewDpsInstallments(Admin $admin): bool
    {
        return $admin->can('view dps installments');
    }
}
