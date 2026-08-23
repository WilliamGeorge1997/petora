<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Branch\Entities\Branch;
use Modules\Product\Entities\Product;
use Modules\Order\Entities\OrderStatus;

class TopProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'prevent-back-history', 'permission:Index-report']);
    }

    public function productReport(Request $request)
    {
        $productsreport = Product::available()->withCount('orderDetails')
            ->withSum('orderDetails', 'total')->orderByDesc('order_details_count')
            ->whereHas('orderDetails.order', function ($query) use ($request) {
                $query->whereOrderStatusId(OrderStatus::DONE)->available();
                if (!empty($request->branch))
                    $query->whereBranchId($request->branch);
                if (!empty($request->from_date) && !empty($request->to_date))
                    $query->whereDate('created_at', '>', $request->from_date)->whereDate('created_at', '<', $request->to_date);
            })
            ->paginate(50);

        $Parameters = @$_SERVER['QUERY_STRING'];
        return view('admin::reports.products', ['Branches' => Branch::available()->get(), 'productsreport' => $productsreport, 'Parameters' => $Parameters]);
    }
}
