<?php

namespace Modules\Doctor\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Doctor\Services\DoctorService;

class DoctorController extends Controller
{
    public function __construct(private DoctorService $doctorService) {}

    public function index(Request $request)
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $doctors = $this->doctorService->active($data);

        return success(true, __('doctor::message.fetched'), $doctors);
    }
}
