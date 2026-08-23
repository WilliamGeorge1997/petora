<?php


namespace Modules\Product\Validation;


trait ProductValidation
{
    protected function validateStore($data){
        return validator($data,[
            'title_ar'=>'required|max:191',
            'title_en'=>'required|max:191',
            // 'description_en'=>'required|max:191',
            // 'description_ar'=>'required|max:191',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
        ]);

    }

    protected function validateUpdate($data){
        return validator($data,[
            'title_ar'=>'required|max:191',
            'title_en'=>'required|max:191',
            // 'description_en'=>'required|max:191',
            // 'description_ar'=>'required|max:191',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
        ]);

    }

    protected function validateStoreAttribute($data){
        return validator($data,[
            'attribute_ar'=>'required|max:191',
            'attribute_en'=>'required|max:191',
            'product_id' => 'required|exists:products,id',
        ]);
    }


    protected function validateUpdateAttribute($data){
        return validator($data,[
            'attribute_ar'=>'required|max:191',
            'attribute_en'=>'required|max:191',
            'product_id' => 'required|exists:products,id',
        ]);
    }

}
