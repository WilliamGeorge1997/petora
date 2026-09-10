<?php

namespace Modules\Clinic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ClinicServiceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
            'schedules' => ['nullable', 'array'],
            'schedules.*.day' => ['required', 'string'],
            'schedules.*.from' => ['required'],
            'schedules.*.to' => ['required'],
        ];
    }

    #[Override]
    public function attributes()
    {
        return [
            'price' => __('clinic::attribute.price'),
            'duration' => __('clinic::attribute.duration'),
            'is_active' => __('clinic::attribute.is_active'),
            'schedules' => __('clinic::attribute.schedules'),
            'schedules.*.day' => __('clinic::attribute.day'),
            'schedules.*.from' => __('clinic::attribute.from'),
            'schedules.*.to' => __('clinic::attribute.to'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'price.required' => __('clinic::message.price_required'),
            'price.numeric' => __('clinic::message.price_numeric'),
            'price.min' => __('clinic::message.price_min'),

            'duration.integer' => __('clinic::message.duration_integer'),
            'duration.min' => __('clinic::message.duration_min'),

            'is_active.boolean' => __('clinic::message.is_active_boolean'),

            'schedules.array' => __('clinic::message.schedules_array'),
            'schedules.*.day.required' => __('clinic::message.day_required'),
            'schedules.*.day.string' => __('clinic::message.day_string'),
            'schedules.*.from.required' => __('clinic::message.from_required'),
            'schedules.*.to.required' => __('clinic::message.to_required'),
        ];
    }
}
