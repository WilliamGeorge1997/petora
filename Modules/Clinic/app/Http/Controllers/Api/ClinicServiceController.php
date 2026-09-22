<?php

namespace Modules\Clinic\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Modules\Clinic\Services\ClinicServiceScheduleTimeService;
use Modules\Clinic\Services\ClinicServiceService;
use Modules\Clinic\Transformers\ClinicServiceResource;

class ClinicServiceController extends Controller
{
    public function __construct(
        private ClinicServiceService $clinicServiceService,
        private ClinicServiceScheduleTimeService $scheduleTimeService
    ) {}

    public function show(int $clinic_service_id)
    {
        $clinicService = $this->clinicServiceService->findById($clinic_service_id, ['service', 'schedules.times']);
        $availability  = $this->scheduleTimeService->availability($clinicService);

        return success(true, __('clinic::message.service.fetched'),
            (new ClinicServiceResource($clinicService))->additional($availability)
        );
    }
}
