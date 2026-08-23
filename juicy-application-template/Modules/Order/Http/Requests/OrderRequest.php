<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Modules\Branch\Entities\Branch;
use Modules\Order\Entities\OrderMethod;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $receipt_in_home_method_id = OrderMethod::RECEIPT_IN_HOME;
        $deliveryFeeMethod = Branch::query()->whereKey($this->branch_id)->value('delivery_fee_method');

        if (Request::isMethod('post')) {
            return [
                'order_method_id' => 'required',
                'payment_method_id' => 'required',
                'branch_id' => 'required|exists:branches,id',
                'details' => 'required|array',
                'details.*.quantity' => 'required',
                'details.*.product_id' => 'required|exists:products,id,is_active,1',
                'details.*.product_type_id' => 'nullable|exists:product_types,id',
                'details.*.attribute_value_id' => 'sometimes|array',
                'details.*.attribute_value_id.*' => 'required|exists:product_attribute_values,id',
                'phone' => 'required',
                'fcm_token' => 'nullable|sometimes|string',
                'address_ar' => 'required_if:order_method_id,' . $receipt_in_home_method_id . '|nullable|string',
                'address_en' => 'required_if:order_method_id,' . $receipt_in_home_method_id . '|nullable|string',
                'lat' => 'required_if:order_method_id,' . $receipt_in_home_method_id . '|nullable|string',
                'long' => 'required_if:order_method_id,' . $receipt_in_home_method_id . '|nullable|string',
                'lang' => 'nullable|string|in:ar,en',
                'table_no' => 'nullable|string',
                'name' => 'nullable|string',
                'delivery_area_id' => [
                    ($deliveryFeeMethod === 'per_area' && ($this->input('order_method_id') == $receipt_in_home_method_id)) ? 'required' : 'nullable',
                    Rule::exists('delivery_areas', 'id')->where(function ($query) {
                        $query->where('branch_id', $this->branch_id);
                    }),
                ],

            ];
        } else {
            return [
                'order_id' => 'sometimes',
                'order_status_id' => 'required',
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
