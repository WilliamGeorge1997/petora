<?php

namespace Modules\Coupon\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Coupon\Enums\CouponType;
use Modules\Coupon\Enums\CouponDiscountOn;
use Illuminate\Validation\Rules\Enum;
use Override;

class CouponRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $couponId = $this->route('coupon') ? $this->route('coupon')->id : null;

        return [
            'code' => ['required', 'string', 'max:255', 'unique:coupons,code,' . $couponId],
            'is_active' => ['nullable', 'boolean'],
            'num_of_uses' => ['nullable', 'integer', 'min:1'],
            'type' => ['required', new Enum(CouponType::class)],
            'value' => ['required', 'numeric', 'min:0'],
            'limit' => ['nullable', 'numeric', 'min:0'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'time_from' => ['nullable', 'date_format:H:i'],
            'time_to' => ['nullable', 'date_format:H:i'],
            'client_uses' => ['nullable', 'integer', 'min:1'],
            'discount_on' => ['required', new Enum(CouponDiscountOn::class)],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    #[Override]
    public function attributes()
    {
        return [
            'code' => __('coupon::attribute.code'),
            'is_active' => __('coupon::attribute.is_active'),
            'num_of_uses' => __('coupon::attribute.num_of_uses'),
            'type' => __('coupon::attribute.type'),
            'value' => __('coupon::attribute.value'),
            'limit' => __('coupon::attribute.limit'),
            'date_from' => __('coupon::attribute.date_from'),
            'date_to' => __('coupon::attribute.date_to'),
            'time_from' => __('coupon::attribute.time_from'),
            'time_to' => __('coupon::attribute.time_to'),
            'client_uses' => __('coupon::attribute.client_uses'),
            'discount_on' => __('coupon::attribute.discount_on'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'code.required' => __('coupon::message.code_required'),
            'code.string' => __('coupon::message.code_string'),
            'code.max' => __('coupon::message.code_max'),
            'code.unique' => __('coupon::message.code_unique'),

            'is_active.boolean' => __('coupon::message.is_active_boolean'),

            'num_of_uses.integer' => __('coupon::message.num_of_uses_integer'),
            'num_of_uses.min' => __('coupon::message.num_of_uses_min'),

            'type.required' => __('coupon::message.type_required'),
            
            'value.required' => __('coupon::message.value_required'),
            'value.numeric' => __('coupon::message.value_numeric'),
            'value.min' => __('coupon::message.value_min'),

            'limit.numeric' => __('coupon::message.limit_numeric'),
            'limit.min' => __('coupon::message.limit_min'),

            'date_from.date' => __('coupon::message.date_from_date'),
            'date_to.date' => __('coupon::message.date_to_date'),
            'date_to.after_or_equal' => __('coupon::message.date_to_after_or_equal'),

            'time_from.date_format' => __('coupon::message.time_from_date_format'),
            'time_to.date_format' => __('coupon::message.time_to_date_format'),

            'client_uses.integer' => __('coupon::message.client_uses_integer'),
            'client_uses.min' => __('coupon::message.client_uses_min'),

            'discount_on.required' => __('coupon::message.discount_on_required'),
        ];
    }
}
