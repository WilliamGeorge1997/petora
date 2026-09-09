<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Order\Services\OrderStatusService;

class OrderStatusController extends Controller
{
    public function __construct(private OrderStatusService $orderStatusService) {}

    public function index(Request $request): JsonResponse
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $orderStatuses = $this->orderStatusService->active($data);

        return success(true, __('order::message.order_statuses_fetched'), $orderStatuses);
    }
}
