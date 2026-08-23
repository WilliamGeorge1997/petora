<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Admin\Entities\Admin;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Common\Helper\FCMService;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Service\OrderService;
use Modules\Order\ViewModel\OrderViewModel;
use Modules\Order\Http\Requests\OrderRequest;

class OrderController extends Controller
{
    private $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->middleware(['auth:admin', 'prevent-back-history']);
        $this->orderService = $orderService;
        $this->middleware('permission:Index-order|Edit-order', ['only' => ['index']]);
        $this->middleware('permission:Edit-order', ['only' => ['edit', 'update']]);
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $data['paginated'] = 50;
        $relation = ['paymentMethod', 'branch', 'orderStatus'];
        if ($request->has('order_status_id')) {
            $orders = $this->orderService->findBy('order_status_id', $request['order_status_id'], $data, $relation);
        } else {
            $orders = $this->orderService->findAll($data, $relation);
        }
        if ($request->ajax()) {
            return response()->json(['data' => $orders->items()]);
        }
        $viewModel = new OrderViewModel();
        return view('order::orders.index', ['orders' => $orders, 'viewModel' => $viewModel]);
    }


    public function show($id)
    {
        $relation = [
            'paymentMethod',
            'branch',
            'rate',
            'orderStatus',
            'branch',
            'details.product',
            'details.attributes.attribute',
            'details.attributes.attributeValue',
            'details.addons.addonValue.addon',
            'details.sides.sideValue.side',
            'histories.status',
        ];
        $order = $this->orderService->findById($id, $relation);
        return view('order::orders.show', compact('order'));
    }

    public function edit($id)
    {
        $viewModel = new OrderViewModel();
        $order = $this->orderService->findById($id);
        return view('order::orders.edit', compact('viewModel', 'order'));
    }

    // to change status from Admin Panel
    public function update(OrderRequest $request, $id)
    {
        if (!empty($request->order_id))  $id = $request->order_id;
        $order = $this->orderService->update($id, ['order_status_id' => $request['order_status_id']]);
        $data = [
            'order_status_id' => $request['order_status_id'],
            'order_id' => $id,
            'notes' => @$request['notes'],
            'user_id' => Auth::id()
        ];
        saveHistory($data, Admin::class);
        if (in_array($request['order_status_id'], [OrderStatus::ACCEPTED_AND_PREPARING, OrderStatus::ORDER_READY, OrderStatus::ORDER_IN_DELIVERY, OrderStatus::CANCELLED])) {
            pushOrderStatusNotify($order);
            $this->sendNotificationToClient($order, $request['order_status_id']);
        }
        return redirect('admin/orders')->with('updated', 'updated');
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
        } elseif ($status_id == OrderStatus::ORDER_IN_DELIVERY) {
            if ($order->lang == 'ar') {
                $data = [
                    'title' => 'الطلب قيد التوصيل',
                    'description' => 'الطلب رقم ' . $order->order_no . ' قيد التوصيل',
                ];
            } else {
                $data = [
                    'title' => 'Order In Delivery',
                    'description' => 'Order ' . $order->order_no . ' is in delivery',
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
        }
        $fcm = new FCMService;
        $order_token = $order->fcm_token;
        if ($order_token ?? null) $fcm->sendNotification($data, [$order_token]);
    }


    public function destroy($id)
    {
        $data = ['order_status_id' => OrderStatus::CANCELLED, 'notes' => $request['notes'] ?? null];
        $order = $this->orderService->update($id, $data);
        pushOrderStatusNotify($order);
        // $this->orderService->delete($id);
        return response()->json(['data' => 'success'], 200);
    }



    // public function ajax_order_time(Request $request)
    // {
    //     return view('order::orders.ajax.order_time', ['order' => $this->orderService->findById($request->id), 'viewModel' =>  new OrderViewModel()]);
    // }

    public function ajax_order_status(Request $request)
    {
        return view('order::orders.ajax.order_status', ['order' => $this->orderService->findById($request->id), 'viewModel' =>  new OrderViewModel()]);
    }

    // public function ajax_driver(Request $request)
    // {
    //     $order = $this->orderService->findById($request->id);
    //     $order->getdistance = $order->getdistance();
    //     return view('order::orders.ajax.driver', ['order' => $order, 'viewModel' =>  new OrderViewModel()]);
    // }

    public function printReceipt($id)
    {
        $relation = [
            'paymentMethod',
            'branch',
            'rate',
            'orderStatus',
            'branch',
            'details.product',
            'details.attributes.attribute',
            'details.attributes.attributeValue',
            'details.addons.addonValue.addon',
        ];
        $order = $this->orderService->findById($id, $relation);
        $settings = getSettingsIn(['name', 'tax_number', 'logo']);
        $order->qr_code = $this->orderService->generateZatcaQRCode($order, $settings);
        return view('order::orders.receipt', compact('order', 'settings'));
    }
}
