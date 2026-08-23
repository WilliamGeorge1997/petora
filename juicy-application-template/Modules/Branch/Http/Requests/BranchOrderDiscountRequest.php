<?php

namespace Modules\Branch\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchOrderDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Authorization is handled in the controller
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_order_discount_enabled' => 'nullable|boolean',
            'order_discounts' => 'required_if:is_order_discount_enabled,1|array|min:1',
            'order_discounts.*.id' => 'nullable|exists:branch_order_discounts,id',
            'order_discounts.*.min_total' => 'required_with:order_discounts|numeric|min:0',
            'order_discounts.*.type' => 'required_with:order_discounts|in:percent,fixed',
            'order_discounts.*.value' => 'required_with:order_discounts|numeric|min:0',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'is_order_discount_enabled' => 'تفعيل الخصم',
            'order_discounts' => 'الخصومات',
            'order_discounts.*.min_total' => 'الحد الأدنى للطلب',
            'order_discounts.*.type' => 'نوع الخصم',
            'order_discounts.*.value' => 'قيمة الخصم',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'order_discounts.required_if' => 'يجب إضافة خصم واحد على الأقل عند تفعيل خاصية الخصم.',
            'order_discounts.min' => 'يجب إضافة خصم واحد على الأقل عند تفعيل خاصية الخصم.',
        ];
    }
}
