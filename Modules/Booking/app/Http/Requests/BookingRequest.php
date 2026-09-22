<?php

namespace Modules\Booking\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class BookingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $clientId = auth('client')->id();

        return [
            'clinic_id' => ['required', 'integer', 'exists:clinics,id'],
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'clinic_service_schedule_time_id' => ['required', 'integer', 'exists:clinic_service_schedule_times,id'],
            'pet_id' => ['required', 'integer', 'exists:pets,id' . ($clientId ? ',client_id,' . $clientId : '')],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],
            'coupon' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
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
            'clinic_id' => __('booking::attribute.clinic_id'),
            'service_id' => __('booking::attribute.service_id'),
            'clinic_service_schedule_time_id' => __('booking::attribute.clinic_service_schedule_time_id'),
            'pet_id' => __('booking::attribute.pet_id'),
            'booking_date' => __('booking::attribute.booking_date'),
            'payment_method_id' => __('booking::attribute.payment_method_id'),
            'coupon' => __('booking::attribute.coupon'),
            'notes' => __('booking::attribute.notes'),
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'clinic_id.required' => __('booking::message.clinic_id_required'),
            'clinic_id.exists' => __('booking::message.clinic_id_exists'),
            
            'service_id.required' => __('booking::message.service_id_required'),
            'service_id.exists' => __('booking::message.service_id_exists'),

            'clinic_service_schedule_time_id.required' => __('booking::message.clinic_service_schedule_time_id_required'),
            'clinic_service_schedule_time_id.exists' => __('booking::message.clinic_service_schedule_time_id_exists'),

            'pet_id.required' => __('booking::message.pet_id_required'),
            'pet_id.exists' => __('booking::message.pet_id_exists'),

            'booking_date.required' => __('booking::message.booking_date_required'),
            'booking_date.date' => __('booking::message.booking_date_date'),
            'booking_date.after_or_equal' => __('booking::message.booking_date_after_or_equal'),

            'payment_method_id.required' => __('booking::message.payment_method_id_required'),
            'payment_method_id.exists' => __('booking::message.payment_method_id_exists'),
        ];
    }
}
