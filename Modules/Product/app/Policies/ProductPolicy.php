<?php

namespace Modules\Product\Policies;

use App\Models\Admin;
use Modules\Product\Models\Product;
use Modules\Admin\Enums\AdminRole;

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
