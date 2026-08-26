<?php

namespace Modules\Company\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class CompanyRequest extends FormRequest
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
            'title_en' => __('company::attribute.title_en'),
            'title_ar' => __('company::attribute.title_ar'),
            'description_en' => __('company::attribute.description_en'),
            'description_ar' => __('company::attribute.description_ar'),
            'address_en' => __('company::attribute.address_en'),
            'address_ar' => __('company::attribute.address_ar'),
            'phone'      => __('company::attribute.phone'),
            'image'      => __('company::attribute.image'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'title_en.required' => __('company::message.title_en_required'),
            'title_en.string'   => __('company::message.title_en_string'),
            
            'title_ar.required' => __('company::message.title_ar_required'),
            'title_ar.string'   => __('company::message.title_ar_string'),
            
            'description_en.string' => __('company::message.description_en_string'),
            'description_ar.string' => __('company::message.description_ar_string'),

            'address_en.string' => __('company::message.address_en_string'),
            'address_ar.string' => __('company::message.address_ar_string'),
            
            'phone.string'      => __('company::message.phone_string'),
            
            'image.image'       => __('company::message.image_invalid'),
            'image.mimes'       => __('company::message.image_mimes'),
            'image.max'         => __('company::message.image_max'),
        ];
    }
}
