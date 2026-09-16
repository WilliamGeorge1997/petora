<?php

namespace Modules\Coupon\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Modules\Admin\Enums\AdminRole;
use Modules\Coupon\DTOs\CouponDto;
use Modules\Coupon\Http\Requests\CouponRequest;
use Modules\Coupon\Models\Coupon;
use Modules\Coupon\Services\CouponService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-coupon|Create-coupon|Edit-coupon|Delete-coupon', only: ['index', 'store'])]
#[Middleware('permission:Create-coupon', only: ['create', 'store'])]
#[Middleware('permission:Edit-coupon', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-coupon', only: ['destroy'])]
class CouponController extends Controller
{
    public function __construct(private CouponService $couponService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $coupons = $this->couponService->findAll($data);
        
        if ($request->ajax()) {
            return success(true, __('coupon::message.fetched'), $coupons->items());
        }

        return view('coupon::coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('coupon::coupons.create');
    }

    public function store(CouponRequest $request)
    {
        $dto = CouponDto::fromRequest($request);
        $this->couponService->save($dto);

        return to_route('admin.coupon.index')->with('success', __('coupon::message.created'));
    }

    public function edit(int $coupon_id)
    {
        $coupon = $this->couponService->findById($coupon_id);
        
        return view('coupon::coupons.edit', compact('coupon'));
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        $dto = CouponDto::fromRequest($request);
        $this->couponService->update($coupon, $dto);

        return to_route('admin.coupon.index')->with('success', __('coupon::message.updated'));
    }

    public function destroy(Coupon $coupon)
    {
        $this->couponService->delete($coupon);

        return success(true, __('coupon::message.deleted'));
    }

    public function activate(Coupon $coupon)
    {
        $coupon = $this->couponService->activate($coupon);

        return success(
            true,
            $coupon->is_active ? __('coupon::message.updated') : __('coupon::message.updated'),
            $coupon
        );
    }
}
