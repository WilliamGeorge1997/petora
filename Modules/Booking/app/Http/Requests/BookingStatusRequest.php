<?php

namespace Modules\Booking\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class BookingStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title_en' => ['required', 'string', 'max:255'],
            'title_ar' => ['required', 'string', 'max:255'],
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
            'title_en' => __('booking::attribute.title_en'),
            'title_ar' => __('booking::attribute.title_ar'),
            'is_active' => __('booking::attribute.is_active'),
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'title_en.required' => __('booking::message.title_en_required'),
            'title_en.string' => __('booking::message.title_en_string'),
            'title_en.max' => __('booking::message.title_en_max'),

            'title_ar.required' => __('booking::message.title_ar_required'),
            'title_ar.string' => __('booking::message.title_ar_string'),
            'title_ar.max' => __('booking::message.title_ar_max'),

            'is_active.boolean' => __('booking::message.is_active_boolean'),
        ];
    }
}
