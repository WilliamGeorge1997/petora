<?php


namespace Modules\Coupon\Validation;


trait DiscountValidation
{
    protected function validateStore($data){
        return validator($data,[
            'title'=>'required',
            'type'=>'required|in:1,2',
            'value'=>'required|numeric',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

    }

    protected function validateUpdate($data){
        return validator($data,[
            'title'=>'required',
            'type'=>'required|in:1,2',
            'value'=>'required|numeric',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

    }


}
