<?php

namespace Modules\Store\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title_en' => ['nullable', 'string', 'max:255'],
            'title_ar' => ['nullable', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:2048'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    #[Override]
    public function attributes(): array
    {
        return [
            'title_en' => __('product::attribute.title_en'),
            'title_ar' => __('product::attribute.title_ar'),
            'description_en' => __('product::attribute.description_en'),
            'description_ar' => __('product::attribute.description_ar'),
            'price' => __('product::attribute.price'),
            'is_active' => __('product::attribute.is_active'),
            'images' => __('product::attribute.images'),
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'title_en.string' => __('product::message.title_en_string'),
            'title_ar.string' => __('product::message.title_ar_string'),

            'description_en.string' => __('product::message.description_en_string'),
            'description_ar.string' => __('product::message.description_ar_string'),

            'price.required' => __('product::message.price_required'),
            'price.numeric' => __('product::message.price_numeric'),
            'price.min' => __('product::message.price_min'),

            'images.array' => __('product::message.images_array'),
            'images.*.image' => __('product::message.images_invalid'),
            'images.*.mimes' => __('product::message.images_mimes'),
            'images.*.max' => __('product::message.images_max'),
        ];
    }
}
