<?php

namespace Modules\Clinic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ClinicRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title_en'   => ['required', 'string'],
            'title_ar'   => ['required', 'string'],
            'description_en' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'address_en' => ['nullable', 'string'],
            'address_ar' => ['nullable', 'string'],
            'phone'      => ['nullable', 'string'],
            'image'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:2048'],
            'lat'        => ['nullable', 'string'],
            'long'       => ['nullable', 'string'],
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
            'title_en'   => __('clinic::attribute.title_en'),
            'title_ar'   => __('clinic::attribute.title_ar'),
            'description_en' => __('clinic::attribute.description_en'),
            'description_ar' => __('clinic::attribute.description_ar'),
            'address_en' => __('clinic::attribute.address_en'),
            'address_ar' => __('clinic::attribute.address_ar'),
            'phone'      => __('clinic::attribute.phone'),
            'image'      => __('clinic::attribute.image'),
            'lat'        => __('clinic::attribute.lat'),
            'long'       => __('clinic::attribute.long'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'title_en.required' => __('clinic::message.title_en_required'),
            'title_en.string'   => __('clinic::message.title_en_string'),
            
            'title_ar.required' => __('clinic::message.title_ar_required'),
            'title_ar.string'   => __('clinic::message.title_ar_string'),

            'description_en.string' => __('clinic::message.description_en_string'),
            'description_ar.string' => __('clinic::message.description_ar_string'),
            
            'address_en.string' => __('clinic::message.address_en_string'),
            'address_ar.string' => __('clinic::message.address_ar_string'),
            
            'phone.string'      => __('clinic::message.phone_string'),
            
            'image.image'       => __('clinic::message.image_invalid'),
            'image.mimes'       => __('clinic::message.image_mimes'),
            'image.max'         => __('clinic::message.image_max'),

            'lat.string'        => __('clinic::message.lat_string'),
            'long.string'       => __('clinic::message.long_string'),
        ];
    }
}
