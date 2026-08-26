<?php

namespace Modules\Clinic\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Clinic\Services\ClinicService;

class ClinicController extends Controller
{
    public function __construct(private ClinicService $clinicService) {}

    public function index(Request $request)
    {
        $clinics = $this->clinicService->active($request->all());
        return success(true, __('clinic::message.fetched'), $clinics);
    }
}
