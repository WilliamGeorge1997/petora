<?php

namespace Modules\Store\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreDeliveryScheduleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'day' => ['required', 'string', 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday'],
            'times' => ['required', 'array', 'min:1'],
            'times.*.id' => ['nullable', 'integer'],
            'times.*.from' => ['required'],
            'times.*.to' => ['required'],
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
            'day' => __('store::attribute.day'),
            'times' => __('store::attribute.times'),
            'times.*.from' => __('store::attribute.from'),
            'times.*.to' => __('store::attribute.to'),
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'day.required' => __('store::message.day_required'),
            'day.string' => __('store::message.day_string'),
            'day.in' => __('store::message.day_in'),
            'times.required' => __('store::message.times_required'),
            'times.array' => __('store::message.times_array'),
            'times.min' => __('store::message.times_min'),
            'times.*.from.required' => __('store::message.times_from_required'),
            'times.*.to.required' => __('store::message.times_to_required'),
        ];
    }
}
