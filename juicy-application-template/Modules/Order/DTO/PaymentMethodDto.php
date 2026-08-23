<?php


namespace Modules\Order\DTO;


class PaymentMethodDto
{

    public $title;
    public $image;
    public $is_active;

    public function __construct($request)
    {

        $this->title = ['en' => $request->get('title_en'), 'ar' => $request->get('title_ar')];
        $this->is_active   = isset($request['is_active']) ? 1 : 0;
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        if ($data['image'] == null) unset($data['image']);
        return $data;
    }
}
