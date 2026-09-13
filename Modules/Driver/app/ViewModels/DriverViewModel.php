<?php

namespace Modules\Driver\ViewModels;

use Modules\Clinic\Services\ClinicService;
use Modules\Country\Services\CountryService;
use Modules\Store\Services\StoreService;

class DriverViewModel
{
    public function stores()
    {
        return (new StoreService)->active(columns: ['id', 'title']);
    }

    public function clinics()
    {
        return (new ClinicService)->active(columns: ['id', 'title']);
    }

    public function countries()
    {
        return (new CountryService)->active(columns: ['id', 'title']);
    }
}
