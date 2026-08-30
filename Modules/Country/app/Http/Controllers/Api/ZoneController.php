<?php

namespace Modules\Country\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Country\Services\ZoneService;

class ZoneController extends Controller
{
    public function __construct(private ZoneService $zoneService) {}

    public function index(Request $request)
    {
        $zones = $this->zoneService->active($request->all());
        return success(true, __('country::message.fetched'), $zones);
    }
}
