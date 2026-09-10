<?php

namespace Modules\Product\ViewModels;

use Modules\Category\Services\CategoryService;

class ProductViewModel
{
    public function categories()
    {
        return (new CategoryService)->active(columns: ['id', 'title']);
    }
}
