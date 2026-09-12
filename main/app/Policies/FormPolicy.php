<?php

namespace App\Policies;

use App\Models\Admin;

class FormPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view kyc settings');
    }

    public function update(Admin $admin): bool
    {
        return $admin->can('update kyc settings');
    }
}
