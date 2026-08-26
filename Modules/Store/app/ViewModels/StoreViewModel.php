<?php

namespace Modules\Store\ViewModels;

use Modules\Company\Services\CompanyService;

class StoreViewModel
{
    public function companies(CompanyService $companyService)
    {
        return $companyService->active(columns:['id','title']);
    }
}
