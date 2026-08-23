<?php

namespace Modules\Branch\ViewModel;

use Modules\Product\Service\ProductService;
use Modules\Order\Service\OrderMethodService;
use Modules\Order\Service\PaymentMethodService;

class BranchViewModel
{
    public function paymentMethods()
    {
        return (new PaymentMethodService())->active();
    }
    public function orderMethods()
    {
        return (new OrderMethodService())->active();
    }
    public function offerProducts($branch_id)
    {
        return (new ProductService())->offerProducts($branch_id);
    }
}
