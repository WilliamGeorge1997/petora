<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Order\DTOs\OrderUpdateDto;
use Modules\Order\Enums\OrderStatus as OrderStatusEnum;
use Modules\Order\Http\Requests\OrderUpdateRequest;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderService;
use Modules\Order\ViewModels\OrderViewModel;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-order|Edit-order', only: ['index'])]
#[Middleware('permission:Edit-order', only: ['edit', 'update'])]
class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    public function index(Request $request): View|JsonResponse
    {
        $relations = ['store', 'clinic', 'paymentMethod', 'orderStatus', 'orderMethod', 'client', 'driver'];
        $data = $request->merge(['paginated' => 50])->all();
        $orders = $this->orderService->findAll($relations, $data);

        if ($request->ajax()) {
            return success(true, __('order::message.fetched'), $orders->items());
        }

        $totalCount = Order::count();
        $sentCount = Order::where('order_status_id', OrderStatusEnum::Sent->value)->count();
        $doneCount = Order::where('order_status_id', OrderStatusEnum::Done->value)->count();
        $cancelCount = Order::where('order_status_id', OrderStatusEnum::Cancelled->value)->count();
        $viewModel = new OrderViewModel;

        return view('order::orders.index', compact('orders', 'totalCount', 'sentCount', 'doneCount', 'cancelCount', 'viewModel'));
    }

    public function edit(int $order_id): View
    {
        $relations = ['orderStatus', 'driver', 'client', 'paymentMethod', 'orderMethod', 'store', 'clinic'];
        $order = $this->orderService->findById($order_id, $relations);
        $viewModel = new OrderViewModel;

        return view('order::orders.edit', compact('order', 'viewModel'));
    }

    public function update(OrderUpdateRequest $request, Order $order): RedirectResponse
    {
        $dto = OrderUpdateDto::fromRequest($request);
        $this->orderService->update($order, $dto);

        return to_route('admin.order.index')->with('success', __('order::message.updated'));
    }

    public function destroy(Order $order): JsonResponse
    {
        $this->orderService->delete($order->id);

        return success(true, __('order::message.deleted'));
    }
}
