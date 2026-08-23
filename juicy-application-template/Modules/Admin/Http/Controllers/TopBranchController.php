<?php

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Branch\Entities\Branch;
use Modules\Order\Entities\OrderStatus;


class TopBranchController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'prevent-back-history', 'permission:Index-report']);
    }

    public function branchesReport(Request $request)
    {
        $currentYear = date("Y");
        if (empty($request->from_date))
            $request->from_date = "$currentYear/1/1";

        if (empty($request->to_date))
            $request->to_date = "$currentYear/12/31";

        $branches = Branch::available()->withCount('orders')
            ->withSum('orders', 'total')->orderByDesc('orders_count')
            ->whereHas('orders', function ($query) use ($request) {
                $query->whereOrderStatusId(OrderStatus::DONE)->available();
                if (!empty($request->branch))
                    $query->WhereIn('branch_id', $request->branch);
                if (!empty($request->from_date) && !empty($request->to_date))
                    $query->whereDate('created_at', '>', $request->from_date)->whereDate('created_at', '<', $request->to_date);
            })
            ->get();

        $branchArray = @$_GET['branch'];
        if (empty($branchArray))  $branchArray = [];
        $BranchesNames = [];
        $BranchesSales = [];
        foreach ($branches  as $key => $value) :
            $BranchesNames[$key] =  $value['title'];
            $BranchesSales[$key] =  $value['orders_sum_total'];
        endforeach;

        $Parameters = @$_SERVER['QUERY_STRING'];

        return view('admin::reports.branches', ['Branches' => Branch::available()->get(), 'branches' => $branches, 'BranchesNames' => $BranchesNames,  'BranchesSales' => $BranchesSales, 'branchArray' => $branchArray, 'Parameters' => $Parameters]);
    }
}
