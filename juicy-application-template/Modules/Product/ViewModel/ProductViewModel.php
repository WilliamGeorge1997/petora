<?php

namespace Modules\Product\ViewModel;

use Modules\Product\Service\SideService;
use Modules\Product\Service\AddonService;
use Modules\Category\Service\CategoryService;


class ProductViewModel
{
    public function categories()
    {
        return (new CategoryService())->active();
    }
    public function addons()
    {
        return (new AddonService())->active();
    }
    public function sides()
    {
        return (new SideService())->active();
    }
}
