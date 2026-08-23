<?php

namespace Modules\Coupon\ViewModel;

use Modules\Branch\Service\BranchService;

class CouponViewModel
{
    public function branches(){
        return (new BranchService())->active();
    }

}
