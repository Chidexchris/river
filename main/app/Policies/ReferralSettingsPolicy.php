<?php

namespace App\Policies;

use App\Models\Admin;

class ReferralSettingsPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view referral settings');
    }

    public function update(Admin $admin): bool
    {
        return $admin->can('update referral settings');
    }

    public function changeStatus(Admin $admin): bool
    {
        return $admin->can('change referral settings status');
    }
}
