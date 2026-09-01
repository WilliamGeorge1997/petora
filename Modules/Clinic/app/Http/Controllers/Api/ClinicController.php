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
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $clinics = $this->clinicService->active($data);
        return success(true, __('clinic::message.fetched'), $clinics);
    }

    public function show(int $clinic_id)
    {
        $clinic = $this->clinicService->findById($clinic_id, ['activeDoctors']);
        return success(true, __('clinic::message.fetched'), $clinic);
    }
}
