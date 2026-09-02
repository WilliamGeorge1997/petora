<?php

namespace Modules\Doctor\Policies;

use Modules\Admin\Models\Admin;
use Modules\Doctor\Models\Doctor;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Enums\AdminRole;

class DoctorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can update the doctor.
     */
    public function update(Admin $admin, Doctor $doctor): bool
    {
        if ($admin->hasRole(AdminRole::SuperAdmin->value)) {
            return true;
        }

        if ($admin->hasRole(AdminRole::ClinicManager->value)) {
            return $admin->clinic_id === $doctor->clinic_id;
        }

        return false;
    }
}
