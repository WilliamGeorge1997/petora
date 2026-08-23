<?php


namespace Modules\Coupon\DTO;


class FreeDeliveryDto
{

    public $date_from;
    public $date_to;
    public $is_active;
    public $branches;

    public function __construct($request)
    {

        if ($request->get('date_from'))$this->date_from = $request->get('date_from');
        if ($request->get('date_to'))$this->date_to = $request->get('date_to');
        if ($request->get('branches'))$this->branches = $request->get('branches');
        $this->is_active   = isset($request['is_active']) ? 1 :0;
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        $data = array_filter($data);
        $data['is_active']   = isset($data['is_active']) ? 1 :0;
        return $data;
    }

}
