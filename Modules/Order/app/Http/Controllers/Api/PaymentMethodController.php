<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Order\Services\PaymentMethodService;

class PaymentMethodController extends Controller
{
    public function __construct(private PaymentMethodService $paymentMethodService) {}

    public function index(Request $request): JsonResponse
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $paymentMethods = $this->paymentMethodService->active($data);

        return success(true, __('order::message.payment_methods_fetched'), $paymentMethods);
    }
}
