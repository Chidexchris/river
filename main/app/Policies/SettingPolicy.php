<?php

namespace App\Policies;

use App\Models\Admin;

class SettingPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view basic and system settings');
    }

    public function updateBasicSettings(Admin $admin): bool
    {
        return $admin->can('update basic settings');
    }

    public function updateBankTransactionSettings(Admin $admin): bool
    {
        return $admin->can('update bank transaction settings');
    }

    public function updateSystemSettings(Admin $admin): bool
    {
        return $admin->can('update system settings');
    }

    public function updateLogoAndFavicon(Admin $admin): bool
    {
        return $admin->can('update logo and favicon');
    }

    public function viewCronSettings(Admin $admin): bool
    {
        return $admin->can('view cronjob settings');
    }
}
