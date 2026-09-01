<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ProductRequest extends FormRequest
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
            'price'      => ['required', 'numeric', 'min:0'],
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
            'title_en'   => __('product::attribute.title_en'),
            'title_ar'   => __('product::attribute.title_ar'),
            'description_en' => __('product::attribute.description_en'),
            'description_ar' => __('product::attribute.description_ar'),
            'price'      => __('product::attribute.price'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'title_en.required' => __('product::message.title_en_required'),
            'title_en.string'   => __('product::message.title_en_string'),
            
            'title_ar.required' => __('product::message.title_ar_required'),
            'title_ar.string'   => __('product::message.title_ar_string'),

            'description_en.string' => __('product::message.description_en_string'),
            'description_ar.string' => __('product::message.description_ar_string'),
            
            'price.required'   => __('product::message.price_required'),
            'price.numeric'    => __('product::message.price_numeric'),
            'price.min'        => __('product::message.price_min'),
        ];
    }
}
