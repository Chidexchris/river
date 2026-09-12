<?php

namespace App\Policies;

use App\Models\Admin;

class GatewayPolicy
{
    public function viewAutomatedGateways(Admin $admin): bool
    {
        return $admin->can('view all automated gateways');
    }

    public function editAutomatedGateway(Admin $admin): bool
    {
        return $admin->can('edit automated gateway');
    }

    public function updateAutomatedGateway(Admin $admin): bool
    {
        return $admin->can('update automated gateway');
    }

    public function removeAutomatedGatewayCurrency(Admin $admin): bool
    {
        return $admin->can('remove automated gateway currency');
    }

    public function changeAutomatedGatewayStatus(Admin $admin): bool
    {
        return $admin->can('change automated gateway status');
    }

    public function viewManualGateway(Admin $admin): bool
    {
        return $admin->can('view all manual gateways');
    }

    public function createManualGateway(Admin $admin): bool
    {
        return $admin->can('create manual gateway');
    }

    public function storeOrUpdateManualGateway(Admin $admin): bool
    {
        return $admin->can('store or update manual gateway');
    }

    public function editManualGateway(Admin $admin): bool
    {
        return $admin->can('edit manual gateway');
    }

    public function changeManualGatewayStatus(Admin $admin): bool
    {
        return $admin->can('change manual gateway status');
    }
}
