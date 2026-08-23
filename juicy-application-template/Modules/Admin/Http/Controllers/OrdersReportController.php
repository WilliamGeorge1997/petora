<?php

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;
use Modules\Admin\ViewModel\AdminViewModel;

class OrdersReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'prevent-back-history', 'permission:Index-report']);
    }

    public function ordersReport(Request $request)
    {
        $orders = Order::available()->when(!empty($request->from_date) && !empty($request->to_date), function ($query) use ($request) {
            $query->whereDate('created_at', '>', $request->from_date)->whereDate('created_at', '<', $request->to_date);
        })
            ->when($request->order_method_id, function ($query) use ($request) {
                $query->whereOrderMethodId($request['order_method_id']);
            })
            ->when($request->payment_method_id, function ($query) use ($request) {

                $query->wherePaymentMethodId($request['payment_method_id']);
            })
            ->when($request->order_status_id, function ($query) use ($request) {
                $query->whereOrderStatusId($request['order_status_id']);
            })
            ->when($request->branch_id, function ($query) use ($request) {
                $query->whereBranchId($request['branch_id']);
            })
            ->latest()->paginate(50);

        $viewModel = new AdminViewModel();

        $Parameters = @$_SERVER['QUERY_STRING'];

        return view('admin::reports.orders', compact('orders', 'viewModel', 'Parameters'));
    }
}
