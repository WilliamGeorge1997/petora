<?php

namespace Modules\Country\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Country\Services\CountryService;

class CountryController extends Controller
{
    public function __construct(private CountryService $countryService) {}

    public function index(Request $request)
    {
        $countries = $this->countryService->active($request->all());
        return success(true, __('country::message.fetched'), $countries);
    }
}
