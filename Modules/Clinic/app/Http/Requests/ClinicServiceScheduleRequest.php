<?php

namespace Modules\Clinic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ClinicServiceScheduleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'day'            => ['required', 'string', 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday'],
            'times'          => ['required', 'array', 'min:1'],
            'times.*.from'   => ['required'],
            'times.*.to'     => ['required'],
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
            'day'          => __('clinic::attribute.day'),
            'times'        => __('clinic::attribute.times'),
            'times.*.from' => __('clinic::attribute.from'),
            'times.*.to'   => __('clinic::attribute.to'),
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'day.required'          => __('clinic::message.day_required'),
            'day.string'            => __('clinic::message.day_string'),
            'day.in'                => __('clinic::message.day_in'),
            'times.required'        => __('clinic::message.times_required'),
            'times.array'           => __('clinic::message.times_array'),
            'times.min'             => __('clinic::message.times_min'),
            'times.*.from.required' => __('clinic::message.times_from_required'),
            'times.*.to.required'   => __('clinic::message.times_to_required'),
        ];
    }
}
