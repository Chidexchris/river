<?php

namespace App\Policies;

use App\Models\Admin;

class MoneyTransferPolicy
{
    public function viewAny(Admin $admin): bool
    {
        return $admin->can('view all money transfers');
    }

    public function viewPendingMoneyTransfers(Admin $admin): bool
    {
        return $admin->can('view pending money transfers');
    }

    public function viewCompletedMoneyTransfers(Admin $admin): bool
    {
        return $admin->can('view completed money transfers');
    }

    public function viewFailedMoneyTransfers(Admin $admin): bool
    {
        return $admin->can('view failed money transfers');
    }

    public function viewInternalMoneyTransfers(Admin $admin): bool
    {
        return $admin->can('view internal money transfers');
    }

    public function viewExternalMoneyTransfers(Admin $admin): bool
    {
        return $admin->can('view external money transfers');
    }

    public function viewWireTransfers(Admin $admin): bool
    {
        return $admin->can('view wire transfers');
    }

    public function downloadFile(Admin $admin): bool
    {
        return $admin->can('download file');
    }

    public function markAsComplete(Admin $admin): bool
    {
        return $admin->can('mark money transfer as complete');
    }

    public function markAsFail(Admin $admin): bool
    {
        return $admin->can('mark money transfer as fail');
    }
}
