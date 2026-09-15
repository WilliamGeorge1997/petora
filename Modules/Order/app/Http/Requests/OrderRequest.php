<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_method_id' => ['required', 'integer'],
            'payment_method_id' => ['required', 'integer'],
            'address_id' => ['required', 'integer', 'exists:addresses,id,client_id,' . auth('client')->id()],
            'delivery_date' => ['required', 'date', 'after_or_equal:today'],
            'store_id' => ['required_without:clinic_id', 'nullable', 'integer', 'exists:stores,id', 'prohibits:clinic_id'],
            'store_delivery_schedule_time_id' => ['required_with:store_id', 'nullable', 'integer', 'exists:store_delivery_schedule_times,id'],
            'clinic_id' => ['required_without:store_id', 'nullable', 'integer', 'exists:clinics,id', 'prohibits:store_id'],
            'clinic_delivery_schedule_time_id' => ['required_with:clinic_id', 'nullable', 'integer', 'exists:clinic_delivery_schedule_times,id'],
            'coupon' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id', 'distinct'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.note' => ['nullable', 'string'],
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
            'address_id' => __('order::attribute.address_id'),
            'order_method_id' => __('order::attribute.order_method_id'),
            'payment_method_id' => __('order::attribute.payment_method_id'),
            'items' => __('order::attribute.items'),
            'items.*.product_id' => __('order::attribute.product_id'),
            'quantity' => __('order::attribute.quantity'),
            'delivery_date' => __('order::attribute.delivery_date'),
            'store_id' => __('order::attribute.store_id'),
            'store_delivery_schedule_time_id' => __('order::attribute.store_delivery_schedule_time_id'),
            'clinic_id' => __('order::attribute.clinic_id'),
            'clinic_delivery_schedule_time_id' => __('order::attribute.clinic_delivery_schedule_time_id'),
            'coupon' => __('order::attribute.coupon'),
            'notes' => __('order::attribute.notes'),
            'items.*.note' => __('order::attribute.note'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'order_method_id.required' => __('order::message.order_method_id_required'),
            'payment_method_id.required' => __('order::message.payment_method_id_required'),

            'address_id.required' => __('order::message.address_id_required'),
            'address_id.exists' => __('order::message.address_id_exists'),
            
            'delivery_date.required' => __('order::message.delivery_date_required'),
            'delivery_date.date' => __('order::message.delivery_date_date'),
            'delivery_date.after_or_equal' => __('order::message.delivery_date_after_or_equal'),

            'items.required' => __('order::message.items_required'),
            'items.array' => __('order::message.items_array'),

            'items.*.product_id.required' => __('order::message.product_id_required'),
            'items.*.product_id.exists' => __('order::message.product_id_exists'),
            'items.*.product_id.distinct' => __('order::message.product_id_distinct'),

            'items.*.quantity.required' => __('order::message.quantity_required'),
            'items.*.quantity.min' => __('order::message.quantity_min'),

            'store_id.required_without' => __('order::message.store_id_required_without'),
            'store_id.exists' => __('order::message.store_id_exists'),
            'store_id.prohibits' => __('order::message.store_id_prohibits'),
            
            'store_delivery_schedule_time_id.required_with' => __('order::message.store_delivery_schedule_time_id_required_with'),
            'store_delivery_schedule_time_id.exists' => __('order::message.store_delivery_schedule_time_id_exists'),

            'clinic_id.required_without' => __('order::message.clinic_id_required_without'),
            'clinic_id.exists' => __('order::message.clinic_id_exists'),
            'clinic_id.prohibits' => __('order::message.clinic_id_prohibits'),
            
            'clinic_delivery_schedule_time_id.required_with' => __('order::message.clinic_delivery_schedule_time_id_required_with'),
            'clinic_delivery_schedule_time_id.exists' => __('order::message.clinic_delivery_schedule_time_id_exists'),
        ];
    }
}
