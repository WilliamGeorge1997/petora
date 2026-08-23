<?php


namespace Modules\Order\DTO;


class OrderStatusDto
{

    public $title;
    public $image;
    public $is_active;

    public function __construct($request)
    {
        $this->title = ['en' => $request->get('title_en'), 'ar' => $request->get('title_ar')];
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        return $data;
    }
}
