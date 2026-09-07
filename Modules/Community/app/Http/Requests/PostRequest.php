<?php

namespace Modules\Community\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class PostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'pet_id'    => ['nullable', 'integer', 'exists:pets,id'],
            'content'   => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'images'    => ['nullable', 'array'],
            'images.*'  => ['image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:2048'],
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
            'client_id' => __('community::attribute.client_id'),
            'pet_id' => __('community::attribute.pet_id'),
            'content' => __('community::attribute.content'),
            'is_active' => __('community::attribute.is_active'),
            'images' => __('community::attribute.images'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'client_id.required' => __('community::message.client_id_required'),
            'pet_id.required' => __('community::message.pet_id_required'),
            'content.required' => __('community::message.content_required'),
            'is_active.required' => __('community::message.is_active_required'),
            'images.required' => __('community::message.images_required'),
        ];
    }
}