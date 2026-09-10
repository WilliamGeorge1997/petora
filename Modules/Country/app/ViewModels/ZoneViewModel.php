<?php

namespace Modules\Country\ViewModels;

use Modules\Country\Services\CityService;

class ZoneViewModel
{
    public function cities()
    {
        return (new CityService)->active(columns: ['id', 'title']);
    }
}
