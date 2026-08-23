<?php

namespace Modules\Coupon\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Coupon\Service\CouponService;

class CouponController extends Controller
{
    private $couponService;
    public function __construct(CouponService $couponService)
    {
        // $this->middleware(['auth:client'])->only('check');
        // $this->middleware(['auth:employee'])->only('index');
        $this->couponService = $couponService;
    }

    public function check(Request $request)
    {
        $response = $this->couponService->checkCoupon($request['code'],$request['branch_id'],Auth::id());
        if (!is_int($response)) return $response;
        $data = $this->couponService->findById($response);
        return return_msg(true, 'Coupon fetched successfully', $data);
    }

    public function index()
    {
        $data['branch_id'] = Auth::user()['branch_id'];
        $data = $this->couponService->findAll($data,['branches']);
        return return_msg(true, 'Coupons fetched successfully', $data);
    }


}
