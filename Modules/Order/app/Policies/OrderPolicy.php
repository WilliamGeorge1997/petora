<?php

namespace Modules\Order\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Admin\Enums\AdminRole;
use Modules\Admin\Models\Admin;
use Modules\Client\Models\Client;
use Modules\Driver\Models\Driver;
use Modules\Order\Models\Order;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any orders.
     */
    public function viewAny(Authenticatable $user): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Index-order');
        }

        if ($user instanceof Client || $user instanceof Driver) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the order.
     */
    public function view(Authenticatable $user, Order $order): bool
    {
        if ($user instanceof Admin) {
            return $this->authorizeAdmin($user, $order);
        }

        if ($user instanceof Client) {
            return $order->client_id === $user->id;
        }

        if ($user instanceof Driver) {
            return $order->driver_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create orders.
     */
    public function create(Authenticatable $user): bool
    {
        if ($user instanceof Client) {
            return true;
        }

        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value) || $user->can('Create-order');
        }

        return false;
    }

    /**
     * Determine whether the user can update or cancel the order.
     */
    public function update(Authenticatable $user, Order $order): bool
    {
        if ($user instanceof Admin) {
            return $this->authorizeAdmin($user, $order);
        }

        if ($user instanceof Client) {
            return $order->client_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the order.
     */
    public function delete(Authenticatable $user, Order $order): bool
    {
        if ($user instanceof Admin) {
            return $user->hasRole(AdminRole::SuperAdmin->value);
        }

        if ($user instanceof Client) {
            return $order->client_id === $user->id;
        }

        return false;
    }

    /**
     * Helper to authorize Admin based on roles (matching StorePolicy).
     */
    private function authorizeAdmin(Admin $admin, Order $order): bool
    {
        if ($admin->hasRole(AdminRole::SuperAdmin->value)) {
            return true;
        }

        if ($admin->hasRole(AdminRole::CompanyManager->value) && $order->store) {
            return $admin->company_id === $order->store->company_id;
        }

        if ($admin->hasRole(AdminRole::StoreManager->value) && $order->store_id) {
            return $admin->store_id === $order->store_id;
        }

        if ($admin->hasRole(AdminRole::ClinicManager->value) && $order->clinic_id) {
            return $admin->clinic_id === $order->clinic_id;
        }

        return false;
    }
}
