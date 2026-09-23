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
use Modules\Order\Transformers\OrderResource;

#[Middleware('auth:client')]
class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index(Request $request)
    {
        $data = $request->merge([
            'pagination_type' => 'cursor',
            'paginated' => $request->input('paginated') ?: 50,
        ])->all();
        $relations = [
            'orderStatus',
            'paymentMethod',
            'details.product:id,title',
            'details.product.firstImage',
            'details.sellerProduct:id,title',
            'details.sellerProduct.firstImage'
        ];
        $columns = ['id','order_no','total','order_status_id','payment_method_id','created_at'];
        $orders = $this->orderService->findBy('client_id', auth('client')->id(), $data, $relations, $columns);
        return success(true, __('order::message.order.fetched'), paginatedResource($orders, OrderResource::class));
    }

    public function show(int $order_id)
    {
        $order = $this->orderService->findById($order_id);
        Gate::authorize('view', $order);
        $order->load([
            'orderStatus',
            'paymentMethod',
            'orderMethod',
            'details.product.images',
            'details.sellerProduct.images',
            'histories.status:id,title',
            'coupon:id,code,type,value,limit,discount_on',
            'store:id,title,description,address,phone,image,latitude,longitude',
            'clinic:id,title,description,address,phone,image,latitude,longitude',
        ]);
        return success(true, __('order::message.order.fetched'), new OrderResource($order));
    }

    public function store(OrderRequest $request)
    {
        $dto = OrderDto::fromRequest($request);
        $orderNo = $this->orderService->save($dto);
        return success(true, __('order::message.order.created'), $orderNo);
    }

    public function update(OrderRequest $request, Order $order)
    {
        Gate::authorize('update', $order);
        $dto = OrderDto::fromRequest($request);
        $order = $this->orderService->update($order, $dto);
        return success(true, __('order::message.order.updated'), $order);
    }

    public function cancel(Request $request, Order $order)
    {
        Gate::authorize('update', $order);

        if ($order->order_status_id !== OrderStatus::Sent->value) {
            return fail(false, __('order::message.order.cannot_cancel'));
        }

        /** @var Client $client */
        $client = auth('client')->user();
        $order = $this->orderService->changeStatusTo(
            $order,
            OrderStatus::Cancelled,
            $client,
            $request->input('notes')
        );
        return success(true, __('order::message.order.cancelled'));
    }

    // public function history(int $order_id, OrderHistoryService $orderHistoryService)
    // {
    //     $order = $this->orderService->findById($order_id);
    //     Gate::authorize('view', $order);
    //     $histories = $orderHistoryService->history($order_id);
    //     return success(true, __('order::message.fetched'), HistoryResource::collection($histories));
    // }

    // public function track(int $order_id)
    // {
    //     $order = $this->orderService->findById($order_id, ['histories.status', 'orderStatus']);
    //     Gate::authorize('view', $order);
    //     $data = $this->orderService->track($order);
    //     return success(true, __('order::message.fetched'), $data);
    // }
}
