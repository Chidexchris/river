<?php

namespace App\Policies;

use App\Models\Admin;

class LoanPlanPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all loan plans');
    }

    public function create(Admin $admin): bool
    {
        return $admin->can('create loan plan');
    }

    public function store(Admin $admin): bool
    {
        return $admin->can('store loan plan');
    }

    public function edit(Admin $admin): bool
    {
        return $admin->can('edit loan plan');
    }

    public function update(Admin $admin): bool
    {
        return $admin->can('update loan plan');
    }

    public function changeStatus(Admin $admin): bool
    {
        return $admin->can('change loan plan status');
    }
}
