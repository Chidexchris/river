<?php

namespace App\Policies;

use App\Models\Admin;

class OtherBankPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view other banks');
    }

    public function create(Admin $admin): bool
    {
        return $admin->can('add other bank');
    }

    public function store(Admin $admin): bool
    {
        return $admin->can('store other bank');
    }

    public function edit(Admin $admin): bool
    {
        return $admin->can('edit other bank');
    }

    public function update(Admin $admin): bool
    {
        return $admin->can('update other bank');
    }

    public function changeStatus(Admin $admin): bool
    {
        return $admin->can('change other bank status');
    }
}
