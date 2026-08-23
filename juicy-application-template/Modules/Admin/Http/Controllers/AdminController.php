<?php

namespace Modules\Admin\Http\Controllers;

use DateTime;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\DTO\AdminDto;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Http\Resources\OrderCardsResource;
use Modules\Admin\Service\AdminService;
use Modules\Admin\Service\RoleService;
use Modules\Admin\Validation\AdminValidation;
use Modules\Admin\ViewModel\AdminViewModel;
use Modules\Common\Helper\FCMService;
use Modules\Common\Helper\UploaderHelper;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderMethod;
use Modules\Order\Entities\OrderStatus;
use Modules\Product\Entities\Product;

class AdminController extends Controller
{
    use UploaderHelper, AdminValidation;

    private $adminService;
    private $AdminViewModel;

    public function __construct(AdminService $adminService, AdminViewModel $AdminViewModel)
    {
        $this->middleware(['auth:admin', 'prevent-back-history']);
        $this->adminService = $adminService;
        $this->middleware('permission:Index-admin|Create-admin|Edit-admin|Delete-admin', ['only' => ['index', 'store']]);
        $this->middleware('permission:Create-admin', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit-admin', ['only' => ['edit', 'update', 'activate']]);
        $this->middleware('permission:Delete-admin', ['only' => ['destroy']]);
        $this->AdminViewModel = $AdminViewModel;
    }

    public function OrderCards()
    {

        $dateMinusOneMinute = (new DateTime)->modify('-1 minutes');
        $dateMinusOneMinute->format('Y-m-d H:i:s');
        $dateMinusTwoMinute = (new DateTime)->modify('-2 minutes');
        $dateMinusTwoMinute->format('Y-m-d H:i:s');
        $dateMinusFifteenMinute = (new DateTime)->modify('-15 minutes');
        $dateMinusFifteenMinute->format('Y-m-d H:i:s');
        $dateMinusTwentyMinute = (new DateTime)->modify('-20 minutes');
        $dateMinusTwentyMinute->format('Y-m-d H:i:s');
        /*****case recent Order */
        $recentMinusOneMinute = Order::whereOrderStatusId(1)->OrderCards()->where('created_at', '>', $dateMinusOneMinute)->get();
        $this->AdminViewModel->recentMinusOneMinute($recentMinusOneMinute);
        $recentMinusTwoMinutes = Order::whereOrderStatusId(1)->OrderCards()->where('created_at', '>=', $dateMinusTwoMinute)->where('created_at', '<', $dateMinusOneMinute)->get();
        $this->AdminViewModel->recentMinusTwoMinutes($recentMinusTwoMinutes);
        $recentPlusTwoMinute = Order::whereOrderStatusId(1)->OrderCards()->where('created_at', '<', $dateMinusTwoMinute)->get();
        // dd($recentPlusTwoMinute);
        $this->AdminViewModel->recentPlusTwoMinute($recentPlusTwoMinute);
        /*****end case */
        /*****case accepted Order */
        $acceptMinus15Minute = Order::whereOrderStatusId(2)->OrderCards()->where('updated_at', '>', $dateMinusFifteenMinute)->get();
        $this->AdminViewModel->acceptMinus15Minute($acceptMinus15Minute);
        $acceptPlus15Minus20Minute = Order::whereOrderStatusId(2)->OrderCards()->where('updated_at', '>=', $dateMinusFifteenMinute)->where('updated_at', '<', $dateMinusTwentyMinute)->get();
        $this->AdminViewModel->acceptPlus15Minus20Minute($acceptPlus15Minus20Minute);
        $acceptPlus20Minute = Order::whereOrderStatusId(2)->OrderCards()->where('updated_at', '<', $dateMinusTwentyMinute)->get();
        $this->AdminViewModel->acceptPlus20Minute($acceptPlus20Minute);
        /*****end case */
        /*****case ready Order */
        $MainReadyData = Order::whereOrderStatusId(3)->OrderCards()->get();
        $this->AdminViewModel->MainReadyData($MainReadyData);
        /*****end case */
        /*****case front of branch Order */
        // $FrontOfBranchMinusOneMinute = Order::whereOrderStatusId(6)->OrderCards()->where('updated_at', '>', $dateMinusOneMinute)->get();
        // $this->AdminViewModel->FrontOfBranchMinusOneMinute($FrontOfBranchMinusOneMinute);
        // $FrontOfBranchMinusTwoMinutes = Order::whereOrderStatusId(6)->OrderCards()->where('updated_at', '>=', $dateMinusTwoMinute)->where('updated_at', '<', $dateMinusOneMinute)->get();
        // $this->AdminViewModel->FrontOfBranchMinusTwoMinutes($FrontOfBranchMinusTwoMinutes);
        // $FrontOfBranchPlusTwoMinute = Order::whereOrderStatusId(6)->OrderCards()->where('updated_at', '<', $dateMinusTwoMinute)->get();
        // $this->AdminViewModel->FrontOfBranchPlusTwoMinute($FrontOfBranchPlusTwoMinute);
        /*****end case */
        $deliveryMinus15Minute = Order::whereOrderStatusId(OrderStatus::ORDER_IN_DELIVERY)->where('order_method_id', OrderMethod::RECEIPT_IN_HOME)->OrderCards()->where('updated_at', '>', $dateMinusFifteenMinute)->get();
        $this->AdminViewModel->deliveryMinus15Minute($deliveryMinus15Minute);

        $deliveryPlus15Minus20Minute = Order::whereOrderStatusId(OrderStatus::ORDER_IN_DELIVERY)->where('order_method_id', OrderMethod::RECEIPT_IN_HOME)->OrderCards()->where('updated_at', '>=', $dateMinusFifteenMinute)->where('updated_at', '<', $dateMinusTwentyMinute)->get();
        $this->AdminViewModel->deliveryPlus15Minus20Minute($deliveryPlus15Minus20Minute);

        $deliveryPlus20Minute = Order::whereOrderStatusId(OrderStatus::ORDER_IN_DELIVERY)->where('order_method_id', OrderMethod::RECEIPT_IN_HOME)->OrderCards()->where('updated_at', '<', $dateMinusTwentyMinute)->get();
        $this->AdminViewModel->deliveryPlus20Minute($deliveryPlus20Minute);

        return array(
            'recentMinusOneMinute' => OrderCardsResource::collection($recentMinusOneMinute)->resolve(),
            'recentMinusTwoMinutes' => OrderCardsResource::collection($recentMinusTwoMinutes)->resolve(),
            'recentPlusTwoMinute' => OrderCardsResource::collection($recentPlusTwoMinute)->resolve(),
            'acceptMinus15Minute' => OrderCardsResource::collection($acceptMinus15Minute)->resolve(),
            'acceptPlus15Minus20Minute' => OrderCardsResource::collection($acceptPlus15Minus20Minute)->resolve(),
            'acceptPlus20Minute' => OrderCardsResource::collection($acceptPlus20Minute)->resolve(),
            'MainReadyData' => OrderCardsResource::collection($MainReadyData)->resolve(),
            // 'FrontOfBranchMinusOneMinute' => OrderCardsResource::collection($FrontOfBranchMinusOneMinute)->resolve(),
            // 'FrontOfBranchMinusTwoMinutes' => OrderCardsResource::collection($FrontOfBranchMinusTwoMinutes)->resolve(),
            // 'FrontOfBranchPlusTwoMinute' => OrderCardsResource::collection($FrontOfBranchPlusTwoMinute)->resolve()
            'deliveryMinus15Minute' => OrderCardsResource::collection($deliveryMinus15Minute)->resolve(),
            'deliveryPlus15Minus20Minute' => OrderCardsResource::collection($deliveryPlus15Minus20Minute)->resolve(),
            'deliveryPlus20Minute' => OrderCardsResource::collection($deliveryPlus20Minute)->resolve(),
        );
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function dashboard(Request $request)
    {
        if (empty(Auth::user()['branch_id'])) {
            $orders = Order::with('paymentMethod', 'orderStatus', 'branch')->take(5)->latest()->get();
            return view('admin::index', compact('orders'));
        }
        /*****case branch dashboard */
        if (!empty(Auth::user()['branch_id'])) {
            $branchId = Auth::user()['branch_id'];
            $OrderCards = $this->OrderCards();
            $viewModel = $this->AdminViewModel;
            if ($request->ajax()) {
                return view('admin::ajaxbranchDashboard', [
                    'recentPlusTwoMinute' => $OrderCards['recentPlusTwoMinute'],
                    'recentMinusOneMinute' => $OrderCards['recentMinusOneMinute'],
                    'recentMinusTwoMinutes' => $OrderCards['recentMinusTwoMinutes'],
                    'acceptMinus15Minute' => $OrderCards['acceptMinus15Minute'],
                    'acceptPlus15Minus20Minute' => $OrderCards['acceptPlus15Minus20Minute'],
                    'acceptPlus20Minute' => $OrderCards['acceptPlus20Minute'],
                    'MainReadyData' => $OrderCards['MainReadyData'],
                    // 'FrontOfBranchMinusOneMinute' => $OrderCards['FrontOfBranchMinusOneMinute'],
                    // 'FrontOfBranchMinusTwoMinutes' => $OrderCards['FrontOfBranchMinusTwoMinutes'],
                    // 'FrontOfBranchPlusTwoMinute' => $OrderCards['FrontOfBranchPlusTwoMinute'],
                    'deliveryMinus15Minute' => $OrderCards['deliveryMinus15Minute'],
                    'deliveryPlus15Minus20Minute' => $OrderCards['deliveryPlus15Minus20Minute'],
                    'deliveryPlus20Minute' => $OrderCards['deliveryPlus20Minute'],
                    'viewModel' => $viewModel
                ]);
            }

            return view('admin::branchDashboard', [
                'recentPlusTwoMinute' => $OrderCards['recentPlusTwoMinute'],
                'recentMinusOneMinute' => $OrderCards['recentMinusOneMinute'],
                'recentMinusTwoMinutes' => $OrderCards['recentMinusTwoMinutes'],
                'acceptMinus15Minute' => $OrderCards['acceptMinus15Minute'],
                'acceptPlus15Minus20Minute' => $OrderCards['acceptPlus15Minus20Minute'],
                'acceptPlus20Minute' => $OrderCards['acceptPlus20Minute'],
                'MainReadyData' => $OrderCards['MainReadyData'],
                // 'FrontOfBranchMinusOneMinute' => $OrderCards['FrontOfBranchMinusOneMinute'],
                // 'FrontOfBranchMinusTwoMinutes' => $OrderCards['FrontOfBranchMinusTwoMinutes'],
                // 'FrontOfBranchPlusTwoMinute' => $OrderCards['FrontOfBranchPlusTwoMinute'],
                'deliveryMinus15Minute' => $OrderCards['deliveryMinus15Minute'],
                'deliveryPlus15Minus20Minute' => $OrderCards['deliveryPlus15Minus20Minute'],
                'deliveryPlus20Minute' => $OrderCards['deliveryPlus20Minute'],
                'viewModel' => $viewModel
            ]);
        }
    }

    public function statistics()
    {
        $date = \Carbon\Carbon::now();
        $lastMonth = $date->copy()->subMonth()->translatedFormat('F');
        $productBase = Product::available()->whereMonth('created_at', '<=', $date->month)->count();

        $availableOrders = Order::available();
        $orders = (clone $availableOrders)->with(['paymentMethod', 'orderStatus', 'branch'])->take(7)->latest()->get();
        $ordersBase = (clone $availableOrders)->whereMonth('created_at', '<=', $date->month);
        $ordersBaseCount = (clone $ordersBase)->count();
        $ordersBaseSum = (clone $ordersBase)->where('order_status_id', OrderStatus::DONE)->sum('total');
        $currentMonthOrders = (clone $availableOrders)->whereMonth('created_at', $date->month);
        $currentMonthOrdersCount = (clone $currentMonthOrders)->count();
        $currentMonthOrdersSum = (clone $currentMonthOrders)->where('order_status_id', OrderStatus::DONE)->sum('total');
        $lastMonthOrders = (clone $availableOrders)->where('order_status_id', OrderStatus::DONE)->whereMonth('created_at', $date->copy()->subMonth()->month);
        $lastMonthOrdersSum = (clone $lastMonthOrders)->sum('total');

        $percantage_compared_current_and_last_month =
            $lastMonthOrdersSum == 0
            ? $currentMonthOrdersSum
            : (($currentMonthOrdersSum - $lastMonthOrdersSum) * 100) / $lastMonthOrdersSum;

        $activeSubscription = (new \Modules\Subscription\Service\SubscriptionService())->activeByBranchId(auth('admin')->user()?->branch_id);
        $currentProductCount = Product::available()->count();

        return view('admin::statistics', compact(['lastMonth', 'orders', 'ordersBase', 'ordersBaseCount', 'ordersBaseSum', 'productBase', 'currentMonthOrders', 'currentMonthOrdersCount', 'currentMonthOrdersSum', 'lastMonthOrders', 'percantage_compared_current_and_last_month', 'activeSubscription', 'currentProductCount']));
    }

    public function updateCardStatus(Request $request)
    {
        $isAjax = $request->ajax();
        $order = Order::whereId($request->order_id);
        if (isset($request->reject)) {
            $order = $order->update(array('order_status_id' => OrderStatus::CANCELLED));
            $data = [
                'order_status_id' => OrderStatus::CANCELLED,
                'order_id' => $request->order_id,
                'notes' => @$request['notes'],
                'user_id' => Auth::id()
            ];
            saveHistory($data, Admin::class);
            $order = Order::whereId($request->order_id)->first();
            pushOrderStatusNotify($order);
            $this->sendNotificationToClient($order, OrderStatus::CANCELLED);
            $msg = 'تم الغاء الطلب بنجاح';
            if ($isAjax) return response()->json(['status' => true, 'msg' => $msg]);
            return redirect()->back()->with(['msg' => $msg]);
        }
        if ($request->order_status_id == 1) {
            $order = $order->update(array('order_status_id' => OrderStatus::ACCEPTED_AND_PREPARING));
            $data = [
                'order_status_id' => OrderStatus::ACCEPTED_AND_PREPARING,
                'order_id' => $request->order_id,
                'notes' => @$request['notes'],
                'user_id' => Auth::id()
            ];
            saveHistory($data, Admin::class);
            $order = Order::whereId($request->order_id)->first();
            pushOrderStatusNotify($order);
            $this->sendNotificationToClient($order, OrderStatus::ACCEPTED_AND_PREPARING);
            $msg = 'تم الموافقة علي الطلب و جاري التحضير';
            if ($isAjax) return response()->json(['status' => true, 'msg' => $msg, 'print_receipt' => true, 'order_id' => $request->order_id]);
            return redirect()->back()->with(['msg' => $msg, 'print_receipt' => true, 'order_id' => $request->order_id]);
        }
        if ($request->order_status_id == OrderStatus::ACCEPTED_AND_PREPARING) {
            $order = $order->update(array('order_status_id' => OrderStatus::ORDER_READY));
            $data = [
                'order_status_id' => OrderStatus::ORDER_READY,
                'order_id' => $request->order_id,
                'notes' => @$request['notes'],
                'user_id' => Auth::id()
            ];
            saveHistory($data, Admin::class);
            $order = Order::whereId($request->order_id)->first();
            pushOrderStatusNotify($order);
            $this->sendNotificationToClient($order, OrderStatus::ORDER_READY);
            $msg = 'الطلب جاهز للتسليم';
            if ($isAjax) return response()->json(['status' => true, 'msg' => $msg]);
            return redirect()->back()->with(['msg' => $msg]);
        }
        if ($request->order_status_id == OrderStatus::ORDER_READY) {
            $data = [
                'order_id' => $request->order_id,
                'notes' => @$request['notes'],
                'user_id' => Auth::id()
            ];
            if ($request->order_method_id == OrderMethod::RECEIPT_IN_HOME) {
                $order = $order->update(array('order_status_id' => OrderStatus::ORDER_IN_DELIVERY));
                $data['order_status_id'] = OrderStatus::ORDER_IN_DELIVERY;
                $msg = 'الطلب قيد التوصيل';
                $order = Order::whereId($request->order_id)->first();
                pushOrderStatusNotify($order);
                $this->sendNotificationToClient($order, OrderStatus::ORDER_IN_DELIVERY);
            } else {
                $order = $order->update(array('order_status_id' => OrderStatus::DONE));
                $data['order_status_id'] = OrderStatus::DONE;
                $msg = 'تم الاستلام بنجاح ';
            }
            saveHistory($data, Admin::class);
            $order = Order::whereId($request->order_id)->first();
            if ($isAjax) return response()->json(['status' => true, 'msg' => $msg]);
            return redirect()->back()->with(['msg' => $msg]);
        }
        if ($request->order_status_id == OrderStatus::ORDER_IN_DELIVERY) {
            $order = $order->update(array('order_status_id' => OrderStatus::DONE));
            $data = [
                'order_status_id' => OrderStatus::DONE,
                'order_id' => $request->order_id,
                'notes' => @$request['notes'],
                'user_id' => Auth::id()
            ];
            saveHistory($data, Admin::class);
            $msg = 'تم الاستلام بنجاح';
            if ($isAjax) return response()->json(['status' => true, 'msg' => $msg]);
            return redirect()->back()->with(['msg' => $msg]);
        }
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $data['paginated'] = 50;
        $relations = ['roles:name', 'branch:id,title'];
        $admins = $this->adminService->findAll($data, $relations);
        $roles = (new RoleService())->findAll(['id', 'name']);
        if ($request->ajax()) {
            return response()->json(['data' => $admins->items()]);
        }
        return view('admin::admins.index', ['admins' => $admins, 'roles' => $roles, 'viewModel' => $this->AdminViewModel]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $roles = (new RoleService())->findAll(['id', 'name']);
        return view('admin::admins.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = (new AdminDto($request))->dataFromRequest();
        $validation = $this->validateStore($data);
        if ($validation->fails()) return redirect()->back()->withInput()->withErrors($validation);
        $admin = $this->adminService->save($data);
        return redirect('admin/admins')->with('created', 'created');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $admin = $this->adminService->findById($id);
        $roles = (new RoleService())->findAll(['id', 'name']);
        $userRole = $admin->roles->pluck('name', 'name')->all();
        $viewModel = new AdminViewModel();
        return view('admin::admins.edit', compact('admin', 'roles', 'userRole', 'viewModel'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $data = (new AdminDto($request))->dataFromRequest();
        $validation = $this->validateUpdate($data, $id);
        if ($validation->fails()) return redirect()->back()->withInput()->withErrors($validation);
        $admin = $this->adminService->update($id, $data);
        return redirect('admin/admins')->with('updated', 'updated');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, Request $request)
    {
        $this->adminService->delete($id);
        return response()->json(['data' => 'success'], 200);
    }

    public function activate($id)
    {
        $this->adminService->activate($id);
        return redirect('admin/admins')->with('updated', 'updated');
    }


    function sendNotificationToClient($order, $status_id)
    {
        if ($status_id == OrderStatus::ACCEPTED_AND_PREPARING) {
            if ($order->lang == 'ar') {
                $data = [
                    'title' => 'تم قبول الطلب',
                    'description' => 'تم قبول الطلب رقم ' . $order->order_no . ' وجاري التحضير',
                ];
            } else {
                $data = [
                    'title' => 'Order Accepted And Preparing',
                    'description' => 'Order ' . $order->order_no . ' has been accepted and is being prepared',
                ];
            }
        } elseif ($status_id == OrderStatus::ORDER_READY) {
            if ($order->lang == 'ar') {
                $data = [
                    'title' => 'الطلب جاهز للتسليم',
                    'description' => 'الطلب رقم ' . $order->order_no . ' جاهز للتسليم',
                ];
            } else {
                $data = [
                    'title' => 'Order Ready For Delivery',
                    'description' => 'Order ' . $order->order_no . ' is ready for delivery',
                ];
            }
        } elseif ($status_id == OrderStatus::CANCELLED) {
            if ($order->lang == 'ar') {
                $data = [
                    'title' => 'تم الغاء الطلب',
                    'description' => 'تم الغاء الطلب رقم ' . $order->order_no,
                ];
            } else {
                $data = [
                    'title' => 'Order Cancelled',
                    'description' => 'Order ' . $order->order_no . ' has been cancelled',
                ];
            }
        } elseif ($status_id == OrderStatus::ORDER_IN_DELIVERY) {
            if ($order->lang == 'ar') {
                $data = [
                    'title' => 'الطلب قيد التوصيل',
                    'description' => 'الطلب رقم ' . $order->order_no . ' قيد التوصيل',
                ];
            }
        }

        $fcm = new FCMService;
        $order_token = $order->fcm_token;
        if ($order_token ?? null) $fcm->sendNotification($data, [$order_token]);
    }
}
