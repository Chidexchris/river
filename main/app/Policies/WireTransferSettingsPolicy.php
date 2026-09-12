<?php

namespace App\Policies;

use App\Models\Admin;

class WireTransferSettingsPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view wire transfer settings');
    }

    public function update(Admin $admin): bool
    {
        return $admin->can('update wire transfer settings');
    }
}
