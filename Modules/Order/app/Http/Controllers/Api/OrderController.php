<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Modules\Order\DTOs\OrderDto;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderService;
use Modules\Order\Transformers\HistoryResource;
use Modules\Order\Transformers\OrderResource;

#[Middleware('auth:client')]
class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index(Request $request)
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $relations = ['orderStatus', 'paymentMethod', 'details.product.images', 'orderMethod', 'branch', 'driver:id,name,phone', 'city', 'zone', 'sub_zone', 'order_request'];
        
        $orders = $this->orderService->findBy('client_id', auth('client')->id(), $data, $relations);

        return success(true, __('order::message.fetched'), paginatedResource($orders, OrderResource::class));
    }

    public function store(OrderRequest $request)
    {
        try {
            $data = OrderDto::fromRequest($request)->dataFromRequest();

            if (!empty($data['coupon'])) {
                $data['coupon_id'] = $this->orderService->checkCoupon($data['coupon'], auth('client')->id());
            }

            $data['items'] = $this->orderService->prepareOrderDetails($data);

            if (empty($data['items'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'items' => __('order::message.no_items_available') ?? 'There are no available items in this moment'
                ]);
            }

            $ids = $this->orderService->save($data);

            return success(true, __('order::message.created'), $ids);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function show(int $order_id)
    {
        $order = $this->orderService->findById($order_id, ['orderStatus', 'paymentMethod', 'details.product.images', 'lastHistory', 'orderMethod', 'city', 'zone', 'sub_zone', 'order_request']);
        Gate::authorize('view', $order);

        return success(true, __('order::message.fetched'), new OrderResource($order));
    }

    public function update(OrderRequest $request, Order $order)
    {
        Gate::authorize('update', $order);
        
        $dto = OrderDto::fromRequest($request);
        $order = $this->orderService->update($order, $dto);

        return success(true, __('order::message.updated'), new OrderResource($order));
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
        
        $this->orderService->cancelOrder($order->id, $request->input('notes'), auth('client')->id());

        return success(true, __('order::message.cancelled'));
    }

    public function orderHistory(int $order_id)
    {
        $order = $this->orderService->findById($order_id);
        Gate::authorize('view', $order);

        $histories = $this->orderService->history($order_id);
        
        return success(true, __('order::message.fetched'), HistoryResource::collection($histories));
    }

    public function orderTrack(int $order_id)
    {
        $order = $this->orderService->findById($order_id);
        Gate::authorize('view', $order);

        $data = $this->orderService->getOrderTrackingData($order_id);
        
        return success(true, __('order::message.fetched'), $data);
    }
}
