<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class PaymentMethodRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title_en'  => ['required', 'string', 'max:255'],
            'title_ar'  => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
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
    public function attributes(): array
    {
        return [
            'title_en'  => __('order::attribute.title_en'),
            'title_ar'  => __('order::attribute.title_ar'),
            'is_active' => __('order::attribute.is_active'),
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'title_en.required' => __('order::message.title_en_required'),
            'title_en.string'   => __('order::message.title_en_string'),
            'title_en.max'      => __('order::message.title_en_max'),

            'title_ar.required' => __('order::message.title_ar_required'),
            'title_ar.string'   => __('order::message.title_ar_string'),
            'title_ar.max'      => __('order::message.title_ar_max'),

            'is_active.boolean' => __('order::message.is_active_boolean'),
        ];
    }
}