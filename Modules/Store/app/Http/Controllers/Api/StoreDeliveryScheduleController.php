<?php

namespace Modules\Store\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Store\Models\Store;
use Modules\Store\Services\StoreDeliveryScheduleService;
use Modules\Store\Transformers\StoreDeliveryScheduleResource;

class StoreDeliveryScheduleController extends Controller
{
    public function __construct(private StoreDeliveryScheduleService $scheduleService) {}

    public function index(Store $store): JsonResponse
    {
        $schedules = $this->scheduleService->getSchedulesForStore($store);

        return success(true, __('store::message.fetched'), StoreDeliveryScheduleResource::collection($schedules));
    }
}
