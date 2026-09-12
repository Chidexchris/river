<?php

namespace App\Policies;

use App\Models\Admin;

class RolePolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all roles');
    }

    public function create(Admin $admin): bool
    {
        return $admin->can('create role');
    }

    public function store(Admin $admin): bool
    {
        return $admin->can('store role');
    }

    public function edit(Admin $admin): bool
    {
        return $admin->can('edit role');
    }

    public function update(Admin $admin): bool
    {
        return $admin->can('update role');
    }
}
