<?php

namespace Modules\Country\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Country\Services\CityService;

class CityController extends Controller
{
    public function __construct(private CityService $cityService) {}

    public function index(Request $request, int $country_id)
    {
        $cities = $this->cityService->active($request->all());
        return success(true, __('country::message.fetched'), $cities);
    }
}
