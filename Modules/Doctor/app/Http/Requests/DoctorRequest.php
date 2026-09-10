<?php

namespace Modules\Doctor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class DoctorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'clinic_id' => ['required', 'exists:clinics,id'],
            'name_en' => ['required', 'string'],
            'name_ar' => ['required', 'string'],
            'specialty_en' => ['required', 'string'],
            'specialty_ar' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:2048'],
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
    public function attributes()
    {
        return [
            'clinic_id' => __('doctor::attribute.clinic_id'),
            'name_en' => __('doctor::attribute.name_en'),
            'name_ar' => __('doctor::attribute.name_ar'),
            'specialty_en' => __('doctor::attribute.specialty_en'),
            'specialty_ar' => __('doctor::attribute.specialty_ar'),
            'image' => __('doctor::attribute.image'),
            'is_active' => __('doctor::attribute.is_active'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'clinic_id.required' => __('doctor::message.clinic_id_required'),
            'clinic_id.exists' => __('doctor::message.clinic_id_exists'),
            'name_en.required' => __('doctor::message.name_en_required'),
            'name_en.string' => __('doctor::message.name_en_string'),

            'name_ar.required' => __('doctor::message.name_ar_required'),
            'name_ar.string' => __('doctor::message.name_ar_string'),

            'specialty_en.required' => __('doctor::message.specialty_en_required'),
            'specialty_en.string' => __('doctor::message.specialty_en_string'),

            'specialty_ar.required' => __('doctor::message.specialty_ar_required'),
            'specialty_ar.string' => __('doctor::message.specialty_ar_string'),

            'image.image' => __('doctor::message.image_invalid'),
            'image.mimes' => __('doctor::message.image_mimes'),
            'image.max' => __('doctor::message.image_max'),
        ];
    }
}
