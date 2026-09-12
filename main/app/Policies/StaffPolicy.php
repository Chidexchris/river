<?php

namespace App\Policies;

use App\Models\Admin;

class StaffPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all branch staffs');
    }

    public function create(Admin $admin): bool
    {
        return $admin->can('create branch staff');
    }

    public function store(Admin $admin): bool
    {
        return $admin->can('store branch staff');
    }

    public function edit(Admin $admin): bool
    {
        return $admin->can('edit branch staff');
    }

    public function update(Admin $admin): bool
    {
        return $admin->can('update branch staff');
    }

    public function changeStaffStatus(Admin $admin): bool
    {
        return $admin->can('change staff status');
    }

    public function loginAsStaff(Admin $admin): bool
    {
        return $admin->can('login as staff');
    }
}
