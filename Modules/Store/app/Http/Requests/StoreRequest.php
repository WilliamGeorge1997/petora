<?php

namespace Modules\Store\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],
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
            'company_id' => __('store::attribute.company_id'),
            'title_en'   => __('store::attribute.title_en'),
            'title_ar'   => __('store::attribute.title_ar'),
            'description_en' => __('store::attribute.description_en'),
            'description_ar' => __('store::attribute.description_ar'),
            'address_en' => __('store::attribute.address_en'),
            'address_ar' => __('store::attribute.address_ar'),
            'phone'      => __('store::attribute.phone'),
            'image'      => __('store::attribute.image'),
            'country_id' => __('store::attribute.country_id'),
            'city_id'    => __('store::attribute.city_id'),
            'zone_id'    => __('store::attribute.zone_id'),
            'latitude'   => __('store::attribute.latitude'),
            'longitude'  => __('store::attribute.longitude'),
            'working_hours' => __('store::attribute.working_hours'),
            'working_hours.*.day' => __('store::attribute.day'),
            'working_hours.*.is_open_24_hours' => __('store::attribute.is_open_24_hours'),
            'working_hours.*.from' => __('store::attribute.from'),
            'working_hours.*.to' => __('store::attribute.to'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'company_id.required' => __('store::message.company_id_required'),
            'company_id.integer'  => __('store::message.company_id_integer'),
            'company_id.exists'   => __('store::message.company_id_exists'),

            'title_en.required' => __('store::message.title_en_required'),
            'title_en.string'   => __('store::message.title_en_string'),
            
            'title_ar.required' => __('store::message.title_ar_required'),
            'title_ar.string'   => __('store::message.title_ar_string'),

            'description_en.string' => __('store::message.description_en_string'),
            'description_ar.string' => __('store::message.description_ar_string'),
            
            'address_en.string' => __('store::message.address_en_string'),
            'address_ar.string' => __('store::message.address_ar_string'),
            
            'phone.string'      => __('store::message.phone_string'),
            
            'image.image'       => __('store::message.image_invalid'),
            'image.mimes'       => __('store::message.image_mimes'),
            'image.max'         => __('store::message.image_max'),

            'country_id.integer' => __('store::message.country_id_integer'),
            'country_id.exists'  => __('store::message.country_id_exists'),

            'city_id.integer' => __('store::message.city_id_integer'),
            'city_id.exists'  => __('store::message.city_id_exists'),

            'zone_id.integer' => __('store::message.zone_id_integer'),
            'zone_id.exists'  => __('store::message.zone_id_exists'),

            'latitude.numeric'   => __('store::message.latitude_numeric'),
            'longitude.numeric'  => __('store::message.longitude_numeric'),
        ];
    }
}
