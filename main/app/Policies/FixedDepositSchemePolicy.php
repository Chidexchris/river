<?php

namespace App\Policies;

use App\Models\Admin;

class FixedDepositSchemePolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all fds');
    }

    public function viewRunningFds(Admin $admin): bool
    {
        return $admin->can('view running fds');
    }

    public function viewClosedFds(Admin $admin): bool
    {
        return $admin->can('view closed fds');
    }

    public function viewFdsInstallments(Admin $admin): bool
    {
        return $admin->can('view fds installments');
    }
}
