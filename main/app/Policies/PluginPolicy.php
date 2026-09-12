<?php

namespace App\Policies;

use App\Models\Admin;

class PluginPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all plugins');
    }

    public function update(Admin $admin): bool
    {
        return $admin->can('update plugin');
    }

    public function changeStatus(Admin $admin): bool
    {
        return $admin->can('change plugin status');
    }
}
