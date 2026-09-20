<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Modules\Client\Models\Client;
use Modules\Order\DTOs\OrderDto;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderHistoryService;
use Modules\Order\Services\OrderService;
use Modules\Order\Transformers\HistoryResource;
use Psy\Readline\Interactive\Input\History;

#[Middleware('auth:client')]
class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index(Request $request)
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $relations = [
            'orderStatus',
            'paymentMethod',
            'details.product.images',
            'orderMethod',
            'branch',
            'driver:id,name,phone',
            'city',
            'zone',
            'sub_zone',
            'order_request'
        ];

        $orders = $this->orderService->findBy('client_id', auth('client')->id(), $data, $relations);

        return success(true, __('order::message.fetched'), $orders);
    }

    public function store(OrderRequest $request)
    {
        $dto = OrderDto::fromRequest($request);
        $orderNo = $this->orderService->save($dto);

        return success(true, __('order::message.created'), $orderNo);
    }

    public function show(int $order_id)
    {
        $order = $this->orderService->findById($order_id, [
            'orderStatus',
            'paymentMethod',
            'details.product.images',
            'lastHistory',
            'orderMethod',
            'city',
            'zone',
            'sub_zone',
            'order_request'
        ]);
        Gate::authorize('view', $order);

        return success(true, __('order::message.fetched'), $order);
    }

    public function update(OrderRequest $request, Order $order)
    {
        Gate::authorize('update', $order);

        $dto = OrderDto::fromRequest($request);
        $order = $this->orderService->update($order, $dto);

        return success(true, __('order::message.updated'), $order);
    }

    public function destroy(Order $order)
    {
        Gate::authorize('delete', $order);

        $this->orderService->delete($order);

        return success(true, __('order::message.deleted'));
    }

    public function cancel(Request $request, Order $order)
    {
        Gate::authorize('update', $order);

        /** @var Client $client */
        $client = auth('client')->user();

        $order = $this->orderService->changeStatusTo(
            orderOrId: $order,
            newStatus: OrderStatus::Cancelled,
            actor: $client,
            notes: $request->input('notes')
        );

        return success(true, __('order::message.cancelled'));
    }

    public function history(int $order_id, OrderHistoryService $orderHistoryService)
    {
        $order = $this->orderService->findById($order_id);
        Gate::authorize('view', $order);

        $histories = $orderHistoryService->history($order_id);

        return success(true, __('order::message.fetched'), HistoryResource::collection($histories));
    }

    public function track(int $order_id)
    {
        $order = $this->orderService->findById($order_id, ['histories.status', 'orderStatus']);
        Gate::authorize('view', $order);

        $data = $this->orderService->track($order);

        return success(true, __('order::message.fetched'), $data);
    }
}
