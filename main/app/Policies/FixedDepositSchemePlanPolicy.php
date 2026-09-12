<?php

namespace App\Policies;

use App\Models\Admin;

class FixedDepositSchemePlanPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all fds plans');
    }

    public function create(Admin $admin): bool
    {
        return $admin->can('create fds plan');
    }

    public function edit(Admin $admin): bool
    {
        return $admin->can('edit fds plan');
    }

    public function changeStatus(Admin $admin): bool
    {
        return $admin->can('change fds plan status');
    }
}
