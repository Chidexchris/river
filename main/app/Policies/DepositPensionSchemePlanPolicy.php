<?php

namespace App\Policies;

use App\Models\Admin;

class DepositPensionSchemePlanPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all dps plans');
    }

    public function create(Admin $admin): bool
    {
        return $admin->can('create dps plan');
    }

    public function edit(Admin $admin): bool
    {
        return $admin->can('edit dps plan');
    }

    public function changeStatus(Admin $admin): bool
    {
        return $admin->can('change dps plan status');
    }
}
