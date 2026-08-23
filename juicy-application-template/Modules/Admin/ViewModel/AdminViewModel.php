<?php


namespace Modules\Admin\ViewModel;

use Modules\Branch\Entities\Branch;

use Modules\Order\Entities\OrderMethod;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Entities\PaymentMethod;

class AdminViewModel
{

    public function orderMethod()
    {
        return (new OrderMethod())->get();
    }
    public function paymentMethod()
    {
        return (new PaymentMethod())->get();
    }
    public function orderStatus()
    {
        return (new OrderStatus())->get();
    }
    public function Branches()
    {
        return (new Branch())->get();
    }
    public function recentMinusOneMinute($Object)
    {
        return   $Object->map(function ($data) {
            $data->OrderStatusVal = 'وصل الان';
            $data->ButtonColor = 'success';
            $data->firstSubmitButton = 'قبول الطلب';
            $data->secondSubmitButton = 'الغاء الطلب';
            return $data;
        });
    }
    public function recentMinusTwoMinutes($Object)
    {
        $Object->map(function ($data) {
            $data->OrderStatusVal = 'وصل منذ اكثر من دقيقة';
            $data->ButtonColor = 'warning';
            $data->firstSubmitButton = 'قبول الطلب';
            $data->secondSubmitButton = 'الغاء الطلب';
            return $data;
        });
    }
    public function recentPlusTwoMinute($Object)
    {
        $Object->map(function ($data) {
            $data->OrderStatusVal = 'وصل منذ اكثر من دقيقتين';
            $data->ButtonColor = 'danger';
            $data->firstSubmitButton = 'قبول الطلب';
            $data->secondSubmitButton = 'الغاء الطلب';
            return $data;
        });
    }

    public function acceptMinus15Minute($Object)
    {
        $Object->map(function ($data) {
            $data->OrderStatusVal = 'يتم التحضير من اقل من 15 دقيقة';
            $data->ButtonColor = 'success';

            // if ($data->order_method_id == 2) {
            $data->firstSubmitButton = 'الطلب جاهز للتسليم';
            // } else {
            // $data->firstSubmitButton = 'Order is ready for delivery';
            // }

            $data->secondSubmitButton = '';
            return $data;
        });
    }

    public function acceptPlus15Minus20Minute($Object)
    {
        $Object->map(function ($data) {
            $data->OrderStatusVal = 'يتم التحضير من اقل من 15 دقيقة';
            $data->ButtonColor = 'warning';
            // if ($data->order_method_id == 2) {
            $data->firstSubmitButton = 'الطلب جاهز للتسليم';
            // } else {
            // $data->firstSubmitButton = 'Order is ready for delivery';
            // }

            $data->secondSubmitButton = '';
            return $data;
        });
    }
    public function acceptPlus20Minute($Object)
    {
        $Object->map(function ($data) {
            $data->OrderStatusVal = 'يتم التحضير منذ اكثر من 20 دقيقة';
            $data->ButtonColor = 'danger';
            // if ($data->order_method_id == 2) {
            $data->firstSubmitButton = 'الطلب جاهز للتسليم';
            // } else {
            // $data->firstSubmitButton = 'Order is ready for delivery';
            // }

            $data->secondSubmitButton = '';
            return $data;
        });
    }
    public function MainReadyData($Object)
    {
        $Object->map(function ($data) {
            $data->OrderStatusVal = '';
            $data->ButtonColor = 'info';
            // if ($data->order_method_id == 3) {
            $data->firstSubmitButton = 'تم التسليم';
            // } else {
            // $data->firstSubmitButton = 'Accepted successfully';
            // }
            // $data->secondSubmitButton = '';
            return $data;
        });
    }

    public function FrontOfBranchMinusOneMinute($Object)
    {
        $Object->map(function ($data) {
            $data->OrderStatusVal = 'وصل منذ دقيقة';
            $data->ButtonColor = 'success';
            // $data->firstSubmitButton = 'Accepted Successfully';
            $data->secondSubmitButton = '';
            return $data;
        });
    }

    public function FrontOfBranchMinusTwoMinutes($Object)
    {
        $Object->map(function ($data) {
            $data->OrderStatusVal = 'وصل منذ دقيقة';
            $data->ButtonColor = 'info';
            // $data->firstSubmitButton = 'Accepted successfully';
            $data->secondSubmitButton = '';
            return $data;
        });
    }

    public function FrontOfBranchPlusTwoMinute($Object)
    {
        $Object->map(function ($data) {
            $data->OrderStatusVal = 'وصل منذ دقيقتين';
            $data->ButtonColor = 'danger';
            // $data->firstSubmitButton = 'Accepted Successfully';
            $data->secondSubmitButton = '';
            return $data;
        });
    }

    public function deliveryMinus15Minute($Object)
    {
        $Object->map(function ($data) {
            $data->OrderStatusVal = 'قيد التوصيل منذ اقل من 15 دقيقة';
            $data->ButtonColor = 'success';
            // $data->firstSubmitButton = 'تم الاستلام';
            // $data->secondSubmitButton = '';
            return $data;
        });
    }
    public function deliveryPlus15Minus20Minute($Object)
    {
        $Object->map(function ($data) {
            $data->OrderStatusVal = 'قيد التوصيل منذ بين 15 و 20 دقيقة';
            $data->ButtonColor = 'warning';
            // $data->firstSubmitButton = 'تم الاستلام';
            // $data->secondSubmitButton = '';
            return $data;
        });
    }
    public function deliveryPlus20Minute($Object)
    {
        $Object->map(function ($data) {
            $data->OrderStatusVal = 'قيد التوصيل منذ اقل من 15 دقيقة';
            $data->ButtonColor = 'danger';
            // $data->firstSubmitButton = 'تم الاستلام';
            // $data->secondSubmitButton = '';
            return $data;
        });
    }
}
