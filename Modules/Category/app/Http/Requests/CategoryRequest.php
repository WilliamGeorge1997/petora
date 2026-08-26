<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class CategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title_en'   => ['required', 'string'],
            'title_ar'   => ['required', 'string'],
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
            'title_en'   => __('category::attribute.title_en'),
            'title_ar'   => __('category::attribute.title_ar'),
            'image'      => __('category::attribute.image'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'title_en.required' => __('category::message.title_en_required'),
            'title_en.string'   => __('category::message.title_en_string'),
            
            'title_ar.required' => __('category::message.title_ar_required'),
            'title_ar.string'   => __('category::message.title_ar_string'),
            
            'image.image'       => __('category::message.image_invalid'),
            'image.mimes'       => __('category::message.image_mimes'),
            'image.max'         => __('category::message.image_max'),
        ];
    }
}
