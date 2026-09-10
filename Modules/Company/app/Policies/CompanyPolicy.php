<?php

namespace Modules\Company\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Enums\AdminRole;
use Modules\Admin\Models\Admin;
use Modules\Company\Models\Company;

class CompanyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can update the company.
     */
    public function update(Admin $admin, Company $company): bool
    {
        if ($admin->hasRole(AdminRole::SuperAdmin->value)) {
            return true;
        }

        if ($admin->hasRole(AdminRole::CompanyManager->value)) {
            return $admin->company_id === $company->id;
        }

        return false;
    }
}
