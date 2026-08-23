<?php

namespace Modules\Coupon\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateCouponRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Request::isMethod('post')) {
            return [
                'code' => 'required|unique:coupons,code',
                'num_of_uses' => 'required|numeric',
                'type' => 'required|in:1,2',
                'value' => 'required|numeric',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date',
                'time_from' => 'nullable|date_format:H:i',
                'time_to' => 'nullable|date_format:H:i',
                'branch_id' => auth()->guard('admin')->user()->hasRole('Super Admin')
                    ? 'required|exists:branches,id'
                    : 'prohibited',
            ];
        } else {
            return [
                'code' => 'required|unique:coupons,code,' . Request::route('coupon'),
                'num_of_uses' => 'required|numeric',
                'type' => 'required|in:1,2',
                'value' => 'required|numeric',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date',
                'time_from' => 'nullable|date_format:H:i',
                'time_to' => 'nullable|date_format:H:i',
                'branch_id' => auth()->guard('admin')->user()->hasRole('Super Admin')
                    ? 'required|exists:branches,id'
                    : 'prohibited',
            ];
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
