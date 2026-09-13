<?php

namespace Modules\Driver\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Enums\AdminRole;
use Modules\Admin\Models\Admin;
use Modules\Driver\Models\Driver;

class DriverPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can update the driver.
     */
    public function update(Admin $admin, Driver $driver): bool
    {
        if ($admin->hasRole(AdminRole::SuperAdmin->value)) {
            return true;
        }

        if ($admin->hasRole(AdminRole::StoreManager->value) && $driver->store_id) {
            return $admin->store_id === $driver->store_id;
        }

        if ($admin->hasRole(AdminRole::ClinicManager->value) && $driver->clinic_id) {
            return $admin->clinic_id === $driver->clinic_id;
        }

        return false;
    }

    /**
     * Determine whether the admin can delete the driver.
     */
    public function delete(Admin $admin, Driver $driver): bool
    {
        return $this->update($admin, $driver);
    }
}
