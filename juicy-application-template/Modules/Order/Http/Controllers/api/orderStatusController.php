<?php

namespace Modules\Order\Http\Controllers\api;

use Illuminate\Routing\Controller;
use Modules\Order\Service\OrderStatusService;

class orderStatusController extends Controller
{
    private $OrderStatusService;
    public function __construct(OrderStatusService $OrderStatusService)
    {
        $this->OrderStatusService = $OrderStatusService;
    }
    public function index()
    {
        $orderStatus = $this->OrderStatusService->findAll();
        return return_msg(true, 'Order Statuses', $orderStatus);
    }
}
