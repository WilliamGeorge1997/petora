<?php

namespace Modules\Store\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Store\Services\StoreDeliveryScheduleService;
use Modules\Store\Transformers\StoreDeliveryScheduleResource;

class StoreDeliveryScheduleController extends Controller
{
    public function __construct(private StoreDeliveryScheduleService $storeDeliveryScheduleService) {}

    public function index(int $store_id): JsonResponse
    {
        $relations = ['times'];
        $schedules = $this->storeDeliveryScheduleService->findBy('store_id', $store_id, relations: $relations);
        return success(true, __('store::message.fetched'), StoreDeliveryScheduleResource::collection($schedules));
    }
}
