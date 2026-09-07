<?php

namespace Modules\Community\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class CommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'post_id'   => ['required', 'integer', 'exists:posts,id'],
            'parent_id' => ['nullable', 'integer', 'exists:comments,id'],
            'content'   => ['required', 'string'],
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
            'client_id' => __('community::attribute.client_id'),
            'post_id' => __('community::attribute.post_id'),
            'parent_id' => __('community::attribute.parent_id'),
            'content' => __('community::attribute.content'),
            'is_active' => __('community::attribute.is_active'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'client_id.required' => __('community::message.client_id_required'),
            'post_id.required' => __('community::message.post_id_required'),
            'parent_id.required' => __('community::message.parent_id_required'),
            'content.required' => __('community::message.content_required'),
            'is_active.required' => __('community::message.is_active_required'),
        ];
    }
}