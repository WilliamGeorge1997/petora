<?php


namespace Modules\Product\DTO;


class AttributeDto
{


    public $product_id;
    public $attribute_ar;
    public $title;
    public $required;
    public $multi_select;
    public $override_price;
    public $attribute_values =[];

    public function __construct($request)
    {


        $this->product_id = $request->get('product_id');
        $this->title = ['en' => $request->get('attribute_en'),'ar' => $request->get('attribute_ar')];
        $this->required   = isset($request['required']) ? 1 :0;
        $this->multi_select   = isset($request['multi_select']) ? 1 :0;
        $this->override_price   = isset($request['override_price']) ? 1 :0;
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        return $data;
    }

}
