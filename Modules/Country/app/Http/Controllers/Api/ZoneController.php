<?php

namespace Modules\Country\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Country\Services\ZoneService;

class ZoneController extends Controller
{
    public function __construct(private ZoneService $zoneService) {}

    public function index(Request $request, int $city_id)
    {
        $conditions = ['city_id' => $city_id, 'is_active' => true];
        $zones = $this->zoneService->findByConditions($conditions, $request->all());

        return success(true, __('country::message.fetched'), $zones);
    }
}
