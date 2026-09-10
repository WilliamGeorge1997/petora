<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Order\DTOs\PaymentMethodDto;
use Modules\Order\Http\Requests\PaymentMethodRequest;
use Modules\Order\Models\PaymentMethod;
use Modules\Order\Services\PaymentMethodService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-paymentmethod|Create-paymentmethod|Edit-paymentmethod|Delete-paymentmethod', only: ['index', 'store'])]
#[Middleware('permission:Create-paymentmethod', only: ['create', 'store'])]
#[Middleware('permission:Edit-paymentmethod', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-paymentmethod', only: ['destroy'])]
class PaymentMethodController extends Controller
{
    public function __construct(private PaymentMethodService $paymentMethodService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $paymentMethods = $this->paymentMethodService->findAll($data);
        if ($request->ajax()) {
            return success(true, __('order::message.fetched'), $paymentMethods->items());
        }

        return view('order::payment_methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('order::payment_methods.create');
    }

    public function store(PaymentMethodRequest $request)
    {
        $dto = PaymentMethodDto::fromRequest($request);
        $this->paymentMethodService->save($dto);

        return to_route('admin.payment_method.index')->with('success', __('order::message.created'));
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('order::payment_methods.edit', compact('paymentMethod'));
    }

    public function update(PaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $dto = PaymentMethodDto::fromRequest($request);
        $this->paymentMethodService->update($paymentMethod, $dto);

        return to_route('admin.payment_method.index')->with('success', __('order::message.updated'));
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $this->paymentMethodService->delete($paymentMethod);

        return success(true, __('order::message.deleted'));
    }

    public function activate(PaymentMethod $paymentMethod)
    {
        $paymentMethod = $this->paymentMethodService->activate($paymentMethod);

        return success(
            true,
            $paymentMethod->is_active ? __('order::message.activated') : __('order::message.deactivated'),
            $paymentMethod
        );
    }
}
