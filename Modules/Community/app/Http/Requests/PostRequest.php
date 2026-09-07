<?php

namespace Modules\Community\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class PostRequest extends FormRequest
{
    #[Override]
    protected function prepareForValidation(): void
    {
        if (auth('client')->check()) {
            $this->merge(['client_id' => auth('client')->id()]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'pet_id'    => ['nullable', 'integer', 'exists:pets,id'],
            'content'   => ['required_without:media', 'nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'media'     => ['required_without:content', 'nullable', 'array'],
            'media.*.file' => ['file', 'mimes:jpeg,png,jpg,webp,svg,gif,mp4,mov,avi', 'max:20480'],
            'media.*.is_video' => ['required_with:media', 'boolean'],
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
            'media' => __('community::attribute.media'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'client_id.required' => __('community::message.client_id_required'),
            'pet_id.required' => __('community::message.pet_id_required'),
            'content.required_without' => __('community::message.content_or_media_required'),
            'is_active.required' => __('community::message.is_active_required'),
            'media.required_without' => __('community::message.content_or_media_required'),
            'media.*.file.file' => __('community::message.media_invalid'),
            'media.*.file.mimes' => __('community::message.media_mimes'),
            'media.*.file.max' => __('community::message.media_max'),
            'media.*.is_video.required_with' => __('community::message.is_video_required'),
        ];
    }
}
