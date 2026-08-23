<?php

namespace Modules\Order\DTO;

use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Carbon\Carbon;
use Modules\Branch\Entities\BranchSetting;

class OrderDto
{
    public $payment_method_id;
    public $client_id;
    public $branch_id;
    public $coupon;
    public $order_method_id;
    public $payment_status;
    public $details;
    public $notes;
    public $phone;
    public $car_no;
    public $car_color;
    public $parking_no;
    public $fcm_token;
    public $address;
    public $lat;
    public $long;
    public $lang;
    public $table_no;
    public $sent_to_whatsapp;
    public $name;

    public function __construct($request)
    {

        $this->client_id = Auth::id();
        if ($request->get('branch_id')) $this->branch_id = $request->get('branch_id');
        if ($request->get('payment_method_id')) $this->payment_method_id = $request->get('payment_method_id');
        if ($request->get('coupon')) $this->coupon = $request->get('coupon');
        if ($request->get('notes')) $this->notes = $request->get('notes');
        $this->order_method_id = $request->get('order_method_id');
        $this->details = $request->get('details');
        $this->phone = $request->get('phone');
        if ($request->get('car_no')) $this->car_no = $request->get('car_no');
        if ($request->get('car_color')) $this->car_color = $request->get('car_color');
        if ($request->get('parking_no')) $this->parking_no = $request->get('parking_no');
        if ($request->get('fcm_token')) $this->fcm_token = $request->get('fcm_token');
        $this->address = ['en' => $request->get('address_en'), 'ar' => $request->get('address_ar')];
        if ($request->get('lat')) $this->lat = $request->get('lat');
        if ($request->get('long')) $this->long = $request->get('long');
        $this->lang = $request->get('lang') ?? 'en';
        if ($request->get('table_no')) $this->table_no = $request->get('table_no');
        $this->sent_to_whatsapp = $this->checkIfSendToWhatsapp($request->get('branch_id'));
        if ($request->get('name')) $this->name = $request->get('name');
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        $data['uuid'] = $this->getOrderNumber();
        $data['order_status_id'] = 1;
        $data['order_no'] = $this->generateOrderNo();
        $data['link_code'] = $this->generateLinkCode();
        return array_filter($data);
    }

    private function generateLinkCode()
    {
        $serial = 'JY-';
        $today = date("Ymd");
        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
        return $serial . $today . $rand;
    }

    private function generateOrderNo()
    {
        $serial = 'JY-';
        $today_orders_count = Order::whereDate('created_at', Carbon::today())->count() + 1;
        $serial .= 100 - date("y");
        $serial .= 100 - date("m");
        $serial .= 100 - date("d");
        $serial .= '-';
        $serial .= str_pad($today_orders_count, 4, '0', STR_PAD_LEFT);
        return $serial;
    }


    private function getOrderNumber()
    {
        $latestOrder = Order::orderBy('created_at', 'DESC')->first();
        $lastId = $latestOrder != null ? $latestOrder->id + 1 : 1;
        return '#' . str_pad($lastId, 6, "0", STR_PAD_LEFT);
    }

    private function checkIfSendToWhatsapp($branch_id)
    {
        return BranchSetting::whereBranchId($branch_id)->value('send_orders_to_whatsapp');
    }
}
