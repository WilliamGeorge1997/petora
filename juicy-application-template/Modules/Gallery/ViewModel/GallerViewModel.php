<?php

namespace Modules\Gallery\ViewModel;

use Modules\Branch\Entities\Branch;

class GallerViewModel
{

    public function findTheme6Branches()
    {
        return Branch::active()->whereHas('settings', function ($query) {
            return  $query->where('theme', 6);
        })->get();
    }
}
