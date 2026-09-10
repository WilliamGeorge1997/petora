<?php

namespace Modules\Product\Policies;

use App\Models\Admin;
use Modules\Admin\Enums\AdminRole;
use Modules\Product\Models\Product;

class ProductPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, Product $product): bool
    {
        if ($admin->role === AdminRole::SuperAdmin->value) {
            return true;
        }

        return false;
    }
}
