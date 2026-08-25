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
            'title.en'   => ['required', 'string'],
            'title.ar'   => ['required', 'string'],
            'address.en' => ['nullable', 'string'],
            'address.ar' => ['nullable', 'string'],
            'phone'      => ['nullable', 'string'],
            'image'      => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
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
            'title.en' => __('company::attribute.title_en'),
            'title.ar' => __('company::attribute.title_ar'),
            'address.en' => __('company::attribute.address_en'),
            'address.ar' => __('company::attribute.address_ar'),
            'phone'      => __('company::attribute.phone'),
            'image'      => __('company::attribute.image'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'title.en.required' => __('company::message.title_en_required'),
            'title.en.string'   => __('company::message.title_en_string'),
            
            'title.ar.required' => __('company::message.title_ar_required'),
            'title.ar.string'   => __('company::message.title_ar_string'),
            
            'address.en.string' => __('company::message.address_en_string'),
            'address.ar.string' => __('company::message.address_ar_string'),
            
            'phone.string'      => __('company::message.phone_string'),
            
            'image.image'       => __('company::message.image_invalid'),
            'image.mimes'       => __('company::message.image_mimes'),
            'image.max'         => __('company::message.image_max'),
        ];
    }
}
