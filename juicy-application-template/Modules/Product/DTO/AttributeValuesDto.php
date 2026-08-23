<?php


namespace Modules\Product\DTO;


class AttributeValuesDto
{


    public $attribute_values =[];

    public function __construct($id,$request)
    {


        foreach ($request->get('attribute_values') as $key => $value){
            if ($request->isMethod('put'))
            {
                $this->attribute_values[$key]['id'] = $value['id'] ?$value['id'] :null;
            }
            $this->attribute_values[$key]['attribute_value'] = ['en'=>$value['value_en'],'ar'=>$value['value_ar']];
            $this->attribute_values[$key]['price'] = $value['price'];
            $this->attribute_values[$key]['product_attribute_id'] = $id;
        }
    }

    public function dataFromRequest()
    {
        $data =  json_decode(json_encode($this), true);
        return $data;
    }

}
