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
            'address_id' => ['required', 'integer', 'exists:addresses,id'],
            'coupon' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'points_discount' => ['nullable', 'boolean'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.store_id' => ['nullable', 'integer', 'exists:stores,id'],
            'items.*.clinic_id' => ['nullable', 'integer', 'exists:clinics,id'],
            'items.*.price' => ['nullable', 'numeric'],
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
            'order_method_id' => __('order::attribute.order_method_id'),
            'payment_method_id' => __('order::attribute.payment_method_id'),
            'address_id' => __('order::attribute.address_id'),
            'coupon' => __('order::attribute.coupon'),
            'notes' => __('order::attribute.notes'),
            'points_discount' => __('order::attribute.points_discount'),
            'items' => __('order::attribute.items'),
            'items.*.product_id' => __('order::attribute.product_id'),
            'items.*.quantity' => __('order::attribute.quantity'),
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
            
            'items.required' => __('order::message.items_required'),
            'items.array' => __('order::message.items_array'),
            
            'items.*.product_id.required' => __('order::message.product_id_required'),
            'items.*.product_id.exists' => __('order::message.product_id_exists'),
            
            'items.*.quantity.required' => __('order::message.quantity_required'),
        ];
    }
}
