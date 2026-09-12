<?php

namespace App\Policies;

use App\Models\Admin;

class SiteDataPolicy
{
    public function viewSeoSettings(Admin $admin): bool
    {
        return $admin->can('view seo settings');
    }

    public function viewThemeSettings(Admin $admin): bool
    {
        return $admin->can('view theme settings');
    }

    public function updateThemeSettings(Admin $admin): bool
    {
        return $admin->can('update theme settings');
    }

    public function viewHomePageSections(Admin $admin): bool
    {
        return $admin->can('view home page sections');
    }

    public function updateHomePageSections(Admin $admin): bool
    {
        return $admin->can('update home page sections');
    }

    public function viewElementContent(Admin $admin): bool
    {
        return $admin->can('view element content');
    }

    public function removeElementContent(Admin $admin): bool
    {
        return $admin->can('remove element content');
    }

    public function viewCookieSettings(Admin $admin): bool
    {
        return $admin->can('view cookie settings');
    }

    public function updateCookieSettings(Admin $admin): bool
    {
        return $admin->can('update cookie settings');
    }

    public function viewMaintenanceSettings(Admin $admin): bool
    {
        return $admin->can('view maintenance settings');
    }

    public function updateMaintenanceSettings(Admin $admin): bool
    {
        return $admin->can('update maintenance settings');
    }
}
