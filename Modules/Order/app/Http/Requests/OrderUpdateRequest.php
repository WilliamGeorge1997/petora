<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_status_id' => ['required', 'integer', 'exists:order_statuses,id'],
            'driver_id' => ['nullable', 'integer', 'exists:drivers,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function attributes()
    {
        return [
            'order_status_id' => __('order::attribute.order_status_id'),
            'driver_id' => __('order::attribute.driver_id'),
            'notes' => __('order::attribute.notes'),
        ];
    }
}
