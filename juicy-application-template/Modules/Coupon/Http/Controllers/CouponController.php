<?php

namespace Modules\Coupon\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Company\Service\Company\CompanyService;
use Modules\Coupon\DTO\CouponDto;
use Modules\Coupon\Http\Requests\CreateCouponRequest;
use Modules\Coupon\Service\CouponService;
use Modules\Coupon\Validation\CouponValidation;
use Modules\Coupon\ViewModel\CouponViewModel;

class CouponController extends Controller
{
    use CouponValidation;
    private $couponService;
    public function __construct(CouponService $couponService)
    {
        $this->middleware(['auth:admin','prevent-back-history']);
        $this->couponService = $couponService;
        $this->middleware('permission:Index-coupon|Create-coupon|Edit-coupon|Delete-coupon', ['only' => ['index','store']]);
        $this->middleware('permission:Create-coupon', ['only' => ['create','store']]);
        $this->middleware('permission:Edit-coupon', ['only' => ['edit','update','activate']]);
        $this->middleware('permission:Delete-coupon', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data= $request->all();
        $data['paginated'] = 50;
        $relations = ['branch'];
        $coupons = $this->couponService->findAll($data, $relations);
        // $viewModel = (new CouponViewModel());
        if($request->ajax()){
            return response()->json(['data' => $coupons->items()]);
        }
        return view('coupon::coupons.index',compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        // $viewModel = new CouponViewModel();
        return view('coupon::coupons.create');
    }

    public function store(CreateCouponRequest $request)
    {
        $this->couponService->save((new CouponDto($request))->dataFromRequest());
        return redirect('/admin/coupons')->with('created','created');
    }



    public function edit($id)
    {
        $coupon = $this->couponService->findById($id);
        // $branches_ids = $coupon->branches()->pluck('id');
        // $viewModel = new CouponViewModel();
        return view('coupon::coupons.edit',compact('coupon'));
    }


    public function update(CreateCouponRequest $request, $id)
    {
        $this->couponService->update($id,(new CouponDto($request))->dataFromRequest());
        return redirect('admin/coupons')->with('updated','updated');
    }

    public function destroy($id,Request $request)
    {
        $this->couponService->delete($id);
        return response()->json(['data' => 'success'],200);
    }

    public function activate($id){
        $this->couponService->activate($id);
        return redirect('admin/coupons')->with('updated','updated');
    }

}
