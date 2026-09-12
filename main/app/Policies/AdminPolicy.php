<?php

namespace App\Policies;

use App\Models\Admin;

class AdminPolicy
{
    public function accessDashboard(Admin $admin): bool
    {
        return $admin->can('access dashboard');
    }

    public function viewNotifications(Admin $admin): bool
    {
        return $admin->can('view all notifications');
    }

    public function markAsRead(Admin $admin): bool
    {
        return $admin->can('mark single notification as read');
    }

    public function markAllAsRead(Admin $admin): bool
    {
        return $admin->can('mark all notifications as read');
    }

    public function removeNotification(Admin $admin): bool
    {
        return $admin->can('remove single notification');
    }

    public function removeAllNotification(Admin $admin): bool
    {
        return $admin->can('remove all notifications');
    }

    public function viewTransactions(Admin $admin): bool
    {
        return $admin->can('view all transactions');
    }

    public function downloadFile(Admin $admin): bool
    {
        return $admin->can('download user file');
    }

    public function viewAdmins(Admin $admin): bool
    {
        return $admin->can('view all admins');
    }

    public function createAdmin(Admin $admin): bool
    {
        return $admin->can('create admin');
    }

    public function editAdmin(Admin $admin): bool
    {
        return $admin->can('edit admin');
    }

    public function changeAdminStatus(Admin $admin): bool
    {
        return $admin->can('change admin status');
    }

    public function loginAsAdmin(Admin $admin): bool
    {
        return $admin->can('login as admin');
    }
}
