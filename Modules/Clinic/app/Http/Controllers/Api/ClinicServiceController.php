<?php

namespace Modules\Clinic\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Clinic\Services\ClinicServiceService;
use Modules\Clinic\Transformers\ClinicServiceResource;

class ClinicServiceController extends Controller
{
    public function __construct(private ClinicServiceService $clinicServiceService) {}

    public function show(int $clinic_service_id)
    {
        $relations = ['service', 'schedules.times'];
        $clinicService = $this->clinicServiceService->findById($clinic_service_id, $relations);

        return success(true, __('clinic::message.service.fetched'), new ClinicServiceResource($clinicService));
    }
}
