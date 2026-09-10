<?php

namespace Modules\Clinic\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Enums\AdminRole;
use Modules\Admin\Models\Admin;
use Modules\Clinic\Models\Clinic;

class ClinicPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can update the clinic.
     */
    public function update(Admin $admin, Clinic $clinic): bool
    {
        if ($admin->hasRole(AdminRole::SuperAdmin->value)) {
            return true;
        }

        return false;
    }
}
