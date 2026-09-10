<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Order\DTOs\OrderStatusDto;
use Modules\Order\Http\Requests\OrderStatusRequest;
use Modules\Order\Models\OrderStatus;
use Modules\Order\Services\OrderStatusService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-orderstatus|Create-orderstatus|Edit-orderstatus|Delete-orderstatus', only: ['index', 'store'])]
#[Middleware('permission:Create-orderstatus', only: ['create', 'store'])]
#[Middleware('permission:Edit-orderstatus', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-orderstatus', only: ['destroy'])]
class OrderStatusController extends Controller
{
    public function __construct(private OrderStatusService $orderStatusService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $orderStatuses = $this->orderStatusService->findAll($data);
        if ($request->ajax()) {
            return success(true, __('order::message.fetched'), $orderStatuses->items());
        }

        return view('order::order_statuses.index', compact('orderStatuses'));
    }

    public function create()
    {
        return view('order::order_statuses.create');
    }

    public function store(OrderStatusRequest $request)
    {
        $dto = OrderStatusDto::fromRequest($request);
        $this->orderStatusService->save($dto);

        return to_route('admin.order_status.index')->with('success', __('order::message.created'));
    }

    public function edit(OrderStatus $orderStatus)
    {
        return view('order::order_statuses.edit', compact('orderStatus'));
    }

    public function update(OrderStatusRequest $request, OrderStatus $orderStatus)
    {
        $dto = OrderStatusDto::fromRequest($request);
        $this->orderStatusService->update($orderStatus, $dto);

        return to_route('admin.order_status.index')->with('success', __('order::message.updated'));
    }

    public function destroy(OrderStatus $orderStatus)
    {
        $this->orderStatusService->delete($orderStatus);

        return success(true, __('order::message.deleted'));
    }

    public function activate(OrderStatus $orderStatus)
    {
        $orderStatus = $this->orderStatusService->activate($orderStatus);

        return success(
            true,
            $orderStatus->is_active ? __('order::message.activated') : __('order::message.deactivated'),
            $orderStatus
        );
    }
}
