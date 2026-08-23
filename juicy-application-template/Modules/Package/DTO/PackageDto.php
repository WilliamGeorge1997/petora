<?php

namespace Modules\Package\DTO;

class PackageDto
{
    public $title;
    public $description;
    public $price;
    public $discounted_price;
    public $duration_months;
    public $is_active;
    public $is_special;
    public $product_count;
    
    public function __construct($request)
    {
        $this->title = ['ar' => $request->get('title_ar'), 'en' => $request->get('title_en'),];
        $this->description = ['ar' => $request->get('description_ar'), 'en' => $request->get('description_en'),];
        $this->price = $request->get('price');
        $this->discounted_price = $request->get('discounted_price');
        $this->duration_months = $request->get('duration_months');
        $this->is_active   = isset($request['is_active']) ? 1 : 0;
        $this->is_special = isset($request['is_special']) ? 1 : 0;
        $this->product_count = $request->get('product_count');
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($data['price'] == null) unset($data['price']);
        if ($data['duration_months'] == null) unset($data['duration_months']);
        if ($data['discounted_price'] == null) unset($data['discounted_price']);
        return $data;
    }
}
