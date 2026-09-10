<?php

namespace Modules\Service\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['nullable', 'integer', 'min:1'],
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
            'title_ar' => __('service::attribute.title_ar'),
            'title_en' => __('service::attribute.title_en'),
            'description_ar' => __('service::attribute.description_ar'),
            'description_en' => __('service::attribute.description_en'),
            'price' => __('service::attribute.price'),
            'duration' => __('service::attribute.duration'),
            'image' => __('service::attribute.image'),
            'is_active' => __('service::attribute.is_active'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'title_en.required' => __('service::message.title_en_required'),
            'title_en.string' => __('service::message.title_en_string'),
            'title_en.max' => __('service::message.title_en_max'),

            'title_ar.required' => __('service::message.title_ar_required'),
            'title_ar.string' => __('service::message.title_ar_string'),
            'title_ar.max' => __('service::message.title_ar_max'),

            'description_en.string' => __('service::message.description_en_string'),
            'description_ar.string' => __('service::message.description_ar_string'),

            'price.required' => __('service::message.price_required'),
            'price.numeric' => __('service::message.price_numeric'),
            'price.min' => __('service::message.price_min'),

            'duration.integer' => __('service::message.duration_integer'),
            'duration.min' => __('service::message.duration_min'),

            'image.image' => __('service::message.image_invalid'),
            'image.mimes' => __('service::message.image_mimes'),
            'image.max' => __('service::message.image_max'),

            'is_active.boolean' => __('service::message.is_active_boolean'),
        ];
    }
}
