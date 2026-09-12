<?php

namespace App\Policies;

use App\Models\Admin;

class NotificationTemplatePolicy
{
    public function viewUniversalTemplate(Admin $admin): bool
    {
        return $admin->can('view universal template');
    }

    public function updateUniversalTemplate(Admin $admin): bool
    {
        return $admin->can('update universal template');
    }

    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all templates');
    }

    public function edit(Admin $admin): bool
    {
        return $admin->can('edit template');
    }

    public function update(Admin $admin): bool
    {
        return $admin->can('update template');
    }

    public function viewEmailConfiguration(Admin $admin): bool
    {
        return $admin->can('view email configuration');
    }

    public function updateEmailConfiguration(Admin $admin): bool
    {
        return $admin->can('update email configuration');
    }

    public function sendTestEmail(Admin $admin): bool
    {
        return $admin->can('send test email');
    }

    public function viewSmsConfiguration(Admin $admin): bool
    {
        return $admin->can('view sms configuration');
    }

    public function updateSmsConfiguration(Admin $admin): bool
    {
        return $admin->can('update sms configuration');
    }

    public function sendTestSms(Admin $admin): bool
    {
        return $admin->can('send test sms');
    }
}
