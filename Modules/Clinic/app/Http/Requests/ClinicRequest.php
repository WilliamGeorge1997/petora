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
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'city_id'    => ['nullable', 'integer', 'exists:cities,id'],
            'zone_id'    => ['nullable', 'integer', 'exists:zones,id'],
            'latitude'   => ['nullable', 'numeric'],
            'longitude'  => ['nullable', 'numeric'],
            'working_hours' => ['nullable', 'array'],
            'working_hours.*.day' => ['required', 'string', 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday'],
            'working_hours.*.is_open_24_hours' => ['nullable', 'boolean'],
            'working_hours.*.from' => ['nullable', 'date_format:H:i'],
            'working_hours.*.to' => ['nullable', 'date_format:H:i'],
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
            'country_id' => __('clinic::attribute.country_id'),
            'city_id'    => __('clinic::attribute.city_id'),
            'zone_id'    => __('clinic::attribute.zone_id'),
            'latitude'   => __('clinic::attribute.latitude'),
            'longitude'  => __('clinic::attribute.longitude'),
            'working_hours' => __('clinic::attribute.working_hours'),
            'working_hours.*.day' => __('clinic::attribute.day'),
            'working_hours.*.is_open_24_hours' => __('clinic::attribute.is_open_24_hours'),
            'working_hours.*.from' => __('clinic::attribute.from'),
            'working_hours.*.to' => __('clinic::attribute.to'),
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

            'country_id.integer' => __('clinic::message.country_id_integer'),
            'country_id.exists'  => __('clinic::message.country_id_exists'),

            'city_id.integer' => __('clinic::message.city_id_integer'),
            'city_id.exists'  => __('clinic::message.city_id_exists'),

            'zone_id.integer' => __('clinic::message.zone_id_integer'),
            'zone_id.exists'  => __('clinic::message.zone_id_exists'),

            'latitude.numeric'   => __('clinic::message.latitude_numeric'),
            'longitude.numeric'  => __('clinic::message.longitude_numeric'),
        ];
    }
}
