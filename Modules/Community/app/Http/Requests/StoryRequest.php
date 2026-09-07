<?php

namespace Modules\Community\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'client_id'  => ['required', 'integer', 'exists:clients,id'],
            'media'      => ['required'], // Could be file or string url
            'is_video'   => ['nullable', 'boolean'],
            'expires_at' => ['nullable', 'date'],
            'is_active'  => ['nullable', 'boolean'],
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
            'media' => __('community::attribute.media'),
            'is_video' => __('community::attribute.is_video'),
            'expires_at' => __('community::attribute.expires_at'),
            'is_active' => __('community::attribute.is_active'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'client_id.required' => __('community::message.client_id_required'),
            'media.required' => __('community::message.media_required'),
            'is_video.required' => __('community::message.is_video_required'),
            'expires_at.required' => __('community::message.expires_at_required'),
            'is_active.required' => __('community::message.is_active_required'),
        ];
    }
}