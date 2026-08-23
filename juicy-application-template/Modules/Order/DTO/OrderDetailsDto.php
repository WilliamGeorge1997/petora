<?php


namespace Modules\Order\DTO;


use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;

class OrderDetailsDto
{

//    public $product_id;
//    public $quantity;
//    public $notes;
    public $details;
    public $order_id;

    public function __construct($request)
    {

    //    if ($request->get('notes'))$this->notes = $request->get('notes');
//        $this->product_id = $request->get('product_id');
//        $this->quantity = $request->get('quantity');
        $this->details = $request->get('details');
        $this->order_id = $request['id'];
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        return array_filter($data);
    }
    



}
