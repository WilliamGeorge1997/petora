<?php

namespace Modules\Category\ViewModel;

use Modules\Category\Service\CategoryService;

class CategoryViewModel
{
    public function active()
    {
        return (new CategoryService())->mainWithoutProducts();
    }
}
