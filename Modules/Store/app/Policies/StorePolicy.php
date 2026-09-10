<?php

namespace Modules\Store\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Enums\AdminRole;
use Modules\Admin\Models\Admin;
use Modules\Store\Models\Store;

class StorePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can update the store.
     */
    public function update(Admin $admin, Store $store): bool
    {
        if ($admin->hasRole(AdminRole::SuperAdmin->value)) {
            return true;
        }

        if ($admin->hasRole(AdminRole::CompanyManager->value)) {
            return $admin->company_id === $store->company_id;
        }

        if ($admin->hasRole(AdminRole::StoreManager->value)) {
            return $admin->store_id === $store->id;
        }

        return false;
    }
}
