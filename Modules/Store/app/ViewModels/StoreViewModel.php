<?php

namespace Modules\Store\ViewModels;

use Modules\Company\Services\CompanyService;
use Modules\Country\Services\CountryService;
class StoreViewModel
{
    public function companies()
    {
        return (new CompanyService())->active(columns: ['id', 'title']);
    }

    public function countries()
    {
        return (new CountryService())->active(columns: ['id', 'title']);
    }
}
