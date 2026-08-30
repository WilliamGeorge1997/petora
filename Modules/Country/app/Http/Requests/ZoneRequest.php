<?php

namespace Modules\Country\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ZoneRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'title_en'   => ['required', 'string'],
            'title_ar'   => ['required', 'string'],
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
            'city_id' => __('country::attribute.city_id'),
            'title_en'   => __('country::attribute.title_en'),
            'title_ar'   => __('country::attribute.title_ar'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'city_id.required' => __('country::message.city_id_required'),
            'city_id.integer'  => __('country::message.city_id_integer'),
            'city_id.exists'   => __('country::message.city_id_exists'),

            'title_en.required' => __('country::message.title_en_required'),
            'title_en.string'   => __('country::message.title_en_string'),
            
            'title_ar.required' => __('country::message.title_ar_required'),
            'title_ar.string'   => __('country::message.title_ar_string'),
        ];
    }
}
