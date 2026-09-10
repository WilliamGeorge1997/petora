<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Order\DTOs\OrderMethodDto;
use Modules\Order\Http\Requests\OrderMethodRequest;
use Modules\Order\Models\OrderMethod;
use Modules\Order\Services\OrderMethodService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-ordermethod|Create-ordermethod|Edit-ordermethod|Delete-ordermethod', only: ['index', 'store'])]
#[Middleware('permission:Create-ordermethod', only: ['create', 'store'])]
#[Middleware('permission:Edit-ordermethod', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-ordermethod', only: ['destroy'])]
class OrderMethodController extends Controller
{
    public function __construct(private OrderMethodService $orderMethodService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $orderMethods = $this->orderMethodService->findAll($data);
        if ($request->ajax()) {
            return success(true, __('order::message.fetched'), $orderMethods->items());
        }

        return view('order::order_methods.index', compact('orderMethods'));
    }

    public function create()
    {
        return view('order::order_methods.create');
    }

    public function store(OrderMethodRequest $request)
    {
        $dto = OrderMethodDto::fromRequest($request);
        $this->orderMethodService->save($dto);

        return to_route('admin.order_method.index')->with('success', __('order::message.created'));
    }

    public function edit(OrderMethod $orderMethod)
    {
        return view('order::order_methods.edit', compact('orderMethod'));
    }

    public function update(OrderMethodRequest $request, OrderMethod $orderMethod)
    {
        $dto = OrderMethodDto::fromRequest($request);
        $this->orderMethodService->update($orderMethod, $dto);

        return to_route('admin.order_method.index')->with('success', __('order::message.updated'));
    }

    public function destroy(OrderMethod $orderMethod)
    {
        $this->orderMethodService->delete($orderMethod);

        return success(true, __('order::message.deleted'));
    }

    public function activate(OrderMethod $orderMethod)
    {
        $orderMethod = $this->orderMethodService->activate($orderMethod);

        return success(
            true,
            $orderMethod->is_active ? __('order::message.activated') : __('order::message.deactivated'),
            $orderMethod
        );
    }
}
