<?php

namespace App\Policies;

use App\Models\Admin;

class BranchPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all branches');
    }

    public function create(Admin $admin): bool
    {
        return $admin->can('create branch');
    }

    public function store(Admin $admin): bool
    {
        return $admin->can('store branch');
    }

    public function edit(Admin $admin): bool
    {
        return $admin->can('edit branch');
    }

    public function update(Admin $admin): bool
    {
        return $admin->can('update branch');
    }

    public function changeStatus(Admin $admin): bool
    {
        return $admin->can('change branch status');
    }
}
