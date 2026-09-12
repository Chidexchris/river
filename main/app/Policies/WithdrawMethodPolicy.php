<?php

namespace App\Policies;

use App\Models\Admin;

class WithdrawMethodPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all withdraw methods');
    }

    public function create(Admin $admin): bool
    {
        return $admin->can('create withdraw method');
    }

    public function storeOrUpdate(Admin $admin): bool
    {
        return $admin->can('store or update withdraw method');
    }

    public function edit(Admin $admin): bool
    {
        return $admin->can('edit withdraw method');
    }

    public function changeStatus(Admin $admin): bool
    {
        return $admin->can('change withdraw method status');
    }
}
