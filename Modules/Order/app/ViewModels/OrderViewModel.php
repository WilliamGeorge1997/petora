<?php

namespace Modules\Order\ViewModels;

use Modules\Driver\Services\DriverService;
use Modules\Order\Services\OrderStatusService;

class OrderViewModel
{
    public function orderStatuses()
    {
        return (new OrderStatusService)->active(columns: ['id', 'title']);
    }

    public function drivers()
    {
        return (new DriverService)->active(columns: ['id', 'name']);
    }
}
