<?php

namespace Modules\Order\ViewModel;

use Modules\Branch\Service\BranchService;
use Modules\Driver\Service\DriverService;
use Modules\Order\Service\OrderStatusService;

class OrderViewModel
{
    public function orderStatuses()
    {
        return (new OrderStatusService())->findAll();
    }

    public function branches()
    {
        return (new BranchService())->findAll();
    }
}
