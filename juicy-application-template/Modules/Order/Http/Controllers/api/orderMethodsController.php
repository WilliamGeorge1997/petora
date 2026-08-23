<?php

namespace Modules\Order\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Branch\Entities\Branch;
use Modules\Order\Service\OrderMethodService;



class orderMethodsController extends Controller
{
    private $orderMethodService;
    public function __construct(OrderMethodService $orderMethodService)
    {
        $this->orderMethodService = $orderMethodService;
    }

    public function index(Request $request)
    {
        $data = $this->orderMethodService->active();
        return return_msg(true,'Order Methods',$data);
    }

}
