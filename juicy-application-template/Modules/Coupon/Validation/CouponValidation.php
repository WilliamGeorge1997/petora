<?php


namespace Modules\Coupon\Validation;


trait CouponValidation
{
    protected function validateStore($data){
        return validator($data,[
            'code'=>'required|unique:coupons,code',
            'num_of_uses'=>'required|numeric',
            'type'=>'required|in:1,2',
            'value'=>'required|numeric',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'time_from' => 'nullable|date_format:H:i',
            'time_to' => 'nullable|date_format:H:i',
            'branches' => 'required|array',
            'branches.*' => 'exists:branches,id'
        ]);

    }

    protected function validateUpdate($data){
        return validator($data,[
            'code'=>'required',
            'num_of_uses'=>'required|numeric',
            'type'=>'required|in:1,2',
            'value'=>'required|numeric',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'time_from' => 'nullable|date_format:H:i',
            'time_to' => 'nullable|date_format:H:i',
        ]);

    }


}
