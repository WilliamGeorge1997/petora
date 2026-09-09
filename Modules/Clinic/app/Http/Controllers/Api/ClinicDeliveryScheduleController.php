<?php

namespace Modules\Clinic\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Clinic\Models\Clinic;
use Modules\Clinic\Services\ClinicDeliveryScheduleService;
use Modules\Clinic\Transformers\ClinicDeliveryScheduleResource;

class ClinicDeliveryScheduleController extends Controller
{
    public function __construct(private ClinicDeliveryScheduleService $scheduleService) {}

    public function index(Clinic $clinic): JsonResponse
    {
        $schedules = $this->scheduleService->getSchedulesForClinic($clinic);

        return success(true, __('clinic::message.fetched'), ClinicDeliveryScheduleResource::collection($schedules));
    }
}
