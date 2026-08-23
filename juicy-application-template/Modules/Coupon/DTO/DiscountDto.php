<?php


namespace Modules\Coupon\DTO;


class DiscountDto
{

    public $title;
    public $is_active;
    public $type;
    public $value;
    public $date_from;
    public $date_to;
    public $company_id;

    public function __construct($request)
    {

        if ($request->get('title'))$this->title = $request->get('title');
        if ($request->get('type'))$this->type = $request->get('type');
        if ($request->get('value'))$this->value = $request->get('value');
        if ($request->get('date_from'))$this->date_from = $request->get('date_from');
        if ($request->get('date_to'))$this->date_to = $request->get('date_to');
        $this->company_id = $request->get('company_id');
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
