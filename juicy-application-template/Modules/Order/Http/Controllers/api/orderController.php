<?php

namespace Modules\Order\Http\Controllers\api;

use Pusher\Pusher;
use Illuminate\Http\Request;
use Modules\Order\DTO\OrderDto;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Service\OrderService;
use Modules\Coupon\Service\CouponService;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Transformers\HistoryResource;

class orderController extends Controller
{
    private $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->findBy('client_id', Auth::id(), ['orderStatus', 'paymentMethod', 'details.product', 'orderMethod'], $request['paginate']);
        return return_msg(true, 'My Orders', $orders);
    }



    public function store(OrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new OrderDto($request))->dataFromRequest();
            if ($data['coupon'] ?? null) {
                $response = (new CouponService())->checkCoupon($data['coupon'], $data['branch_id']);
                if (!is_int($response)) return $response;
                $data['coupon_id'] = $response;
            }
            $order = $this->orderService->save($data);
            $data['order_id'] = $order->id;
            saveHistory($data, 'Client');
            // $this->sendNotificationToAdmins($order->id);
            $this->addOrderpusher($order);
            // broadcast(new createOrder($order->id))->toOthers();
            // $this->orderService->StoreOrderStatus($order->id,$data['employee_id'],$order->order_status_id);
            // if($data['payment_method_id'] == 2){
            //     try{
            //         $user = Client::find($order->client_id);
            //         $payment = $user->pay($order->total,['udf1' => $order->id,'udf2' => 'LOZ']);
            //         $payment->url; // this will return payment link
            //     } catch(\Asciisd\Knet\Exceptions\PaymentActionRequired $exception) {
            //         $transaction =  $exception->payment->asKnetTransaction();
            //         $order->update(['track_id' => $transaction['trackid'],'order_Status_id'=> 10]);
            //         return return_msg(true, 'Payment gateway url ', $transaction['url']);;
            //     }
            // }
            DB::commit();
            return return_msg(true, 'Order Created Successfully', $order);
        } catch (\Exception $e) {
            DB::rollBack();
            return return_msg(false, $e->getMessage(), null, 404);
        }
    }
    public function addOrderpusher($order)
    {
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        );
        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );
        $pusher->trigger('createOrder-notify-channel', 'Modules\\Order\\Events\\CreateOrderNotify', $order);
    }
    // function sendNotificationToAdmins($order_id)
    // {
    //     $admins = (new AdminService())->active();
    //     $data['title'] = 'طلب شراء جديد';
    //     $data['description'] = 'طلب شراء جديد رقم ' . $order_id;
    //     $data['order_id'] = $order_id;
    //     foreach ($admins as $admin) {
    //         $data['user_id'] = $admin->id;
    //         (new NotificationService())->save($data, Admin::class);
    //     }
    // }

    public function show($id)
    {
        $orders = $this->orderService->findById($id, [
            'orderStatus',
            'paymentMethod',
            'details.product',
            'details.attributes.attribute',
            'details.attributes.attributeValue',
            'details.addons.addon',
            'details.addons.addonValue',
            'orderMethod',
            'details.sides.side',
            'details.sides.sideValue',
        ]);
        return return_msg(true, 'Order Details', $orders);
    }

    // to cancel order
    public function cancel(Request $request, $id)
    {
        $this->orderService->update($id, ['order_status_id' => OrderStatus::CANCELLED]);
        $data = [
            'order_status_id' => OrderStatus::CANCELLED,
            'order_id' => $id,
            'notes' => @$request['notes'],
            // 'user_id' => Auth::id()
        ];
        saveHistory($data, 'Client');
        return return_msg(true, 'Order Cancelled Sucessfully');
    }



    // public function update(Request $request, $id)
    // {
    //     $data = $request->except('_token');

    //     $validation = $this->validateUpdate($data);
    //     if ($validation->fails()) return return_msg(false,'Validation Errors',
    //         ['validation_errors' => $validation->getMessageBag()],
    //         'validation_error');
    //         if ($data['coupon'] ?? null){
    //             $response = $this->checkCoupon($data['coupon'],Auth::user()['branch_id']);
    //             if (!is_int($response)) return $response;
    //             $data['coupon_id'] = $response;
    //         }
    //     $this->orderService->payLater($id,$data);
    //     return return_msg(true,'Order paid Successfully');
    // }


    public function destroy($id, Request $request)
    {
        $this->orderService->delete($id);
        return response()->json(['data' => 'success'], 200);
    }

    public function orderHistory($id)
    {
        $histories = (new OrderService())->history($id);
        $histories = HistoryResource::collection($histories);
        return return_msg(true, 'Order History', $histories);
    }

    // public function orderTrack($id)
    // {
    //     $status_ids = (new OrderService())->historyStatusIds($id);
    //     $data['order_id'] = $id;
    //     $data['duration_time'] = (new OrderService())->findById($id)['duration_time'];
    //     $data['status_ids'] = $status_ids;
    //     $data['last_Status_title'] = (new OrderStatusService())->findById($status_ids[count($status_ids) - 1])->getTranslations('title');
    //     return return_msg(true, 'Order Track', $data);
    // }

    // public function clientInFrontOfBranch($id)
    // {
    //     (new OrderService())->update($id, ['order_status_id' => 6]);
    //     $data['order_id'] = $id;
    //     $data['order_status_id'] = 6;
    //     $data['client_id'] = Auth::id();
    //     saveHistory($data, Client::class);
    //     $this->sendInFrontOfBranchNotificationToAdmins($id);
    //     return return_msg(true, 'Order Updated Successfully');
    // }

    // function sendInFrontOfBranchNotificationToAdmins($order_id)
    // {
    //     Notification::whereOrderId($order_id)->where('notifiable_type', Admin::class)->delete();
    //     $admins = (new AdminService())->active();
    //     $data['title'] = 'العميل امام الفرع';
    //     $data['description'] = 'العميل صاحب طلب رقم  ' . $order_id . ' امام الفرع الان';
    //     $data['order_id'] = $order_id;
    //     foreach ($admins as $admin) {
    //         $data['user_id'] = $admin->id;
    //         (new NotificationService())->save($data, Admin::class);
    //     }
    // }
}
