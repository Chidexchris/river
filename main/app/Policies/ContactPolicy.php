<?php

namespace App\Policies;

use App\Models\Admin;

class ContactPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all contacts');
    }

    public function delete(Admin $admin): bool
    {
        return $admin->can('remove contact');
    }

    public function changeStatus(Admin $admin): bool
    {
        return $admin->can('change contact status');
    }
}
