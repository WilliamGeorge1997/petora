<?php

namespace Modules\Order\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Service\PaymentMethodService;



class paymentMethodsController extends Controller
{
    private $paymentMethodService;
    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }

    public function index(Request $request)
    {
        $data = $this->paymentMethodService->active();
        return return_msg(true,'Payment Methods',$data);
    }


}
