<?php

namespace Modules\Category\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Enums\AdminRole;
use Modules\Admin\Models\Admin;
use Modules\Category\Models\Category;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the admin can update the category.
     */
    public function update(Admin $admin, Category $category): bool
    {
        if ($admin->hasRole(AdminRole::SuperAdmin->value)) {
            return true;
        }

        return false;
    }
}
