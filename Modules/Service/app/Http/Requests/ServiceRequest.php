<?php

namespace Modules\Service\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title_ar'       => ['required', 'string', 'max:255'],
            'title_en'       => ['required', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'duration'       => ['required', 'integer', 'min:1'],
            'image'          => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:2048'],
            'is_active'      => ['nullable', 'boolean'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'title_ar'       => __('service::attribute.title_ar'),
            'title_en'       => __('service::attribute.title_en'),
            'description_ar' => __('service::attribute.description_ar'),
            'description_en' => __('service::attribute.description_en'),
            'price'          => __('service::attribute.price'),
            'duration'       => __('service::attribute.duration'),
            'image'          => __('service::attribute.image'),
            'is_active'      => __('service::attribute.is_active'),
        ];
    }
}
