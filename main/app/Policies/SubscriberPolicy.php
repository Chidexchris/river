<?php

namespace App\Policies;

use App\Models\Admin;

class SubscriberPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all subscribers');
    }

    public function delete(Admin $admin): bool
    {
        return $admin->can('remove subscriber');
    }

    public function sendEmail(Admin $admin): bool
    {
        return $admin->can('send email to subscribers');
    }
}
