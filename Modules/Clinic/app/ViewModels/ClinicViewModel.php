<?php

namespace Modules\Clinic\ViewModels;

use Modules\Country\Services\CountryService;

class ClinicViewModel
{
    public function countries()
    {
        return (new CountryService)->active(columns: ['id', 'title']);
    }
}
