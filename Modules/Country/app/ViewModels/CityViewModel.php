<?php

namespace Modules\Country\ViewModels;

use Modules\Country\Services\CountryService;

class CityViewModel
{
    public function countries()
    {
        return (new CountryService)->active(columns: ['id', 'title']);
    }
}
