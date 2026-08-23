<?php

namespace Modules\Branch\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admin\Entities\Admin;
use Modules\Branch\Entities\Branch;

class BranchPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return boolean
     */
    public function update(Admin $admin, Branch $branch)
    {
        if ($admin->hasRole('Branch Manager')) {
            return $admin->branch_id === $branch->id;
        }
        return true;
    }
}
