<?php

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Branch\Entities\Branch;
use Modules\Category\Entities\Category;
use Modules\Order\Entities\OrderStatus;


class TopCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'prevent-back-history', 'permission:Index-report']);
    }

    public function categoryReport(Request $request)
    {

        $categoriesreport = Category::available()->withCount('orderDetails')
            ->withSum('orderDetails', 'total')->orderByDesc('order_details_count')
            ->whereHas('orderDetails.order', function ($query) use ($request) {
                $query->whereOrderStatusId(OrderStatus::DONE)->available();
                if (!empty($request->branch))
                    $query->whereBranchId($request->branch);
                if (!empty($request->from_date) && !empty($request->to_date))
                    $query->whereDate('created_at', '>', $request->from_date)->whereDate('created_at', '<', $request->to_date);
            })
            ->get();

        //return      $categoriesreport;

        $Parameters = @$_SERVER['QUERY_STRING'];

        return view('admin::reports.categories', ['Branches' => Branch::available()->get(), 'categoriesreport' => $categoriesreport, 'Parameters' => $Parameters]);
    }
}
