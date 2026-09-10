<?php

namespace Modules\Community\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class CommentRequest extends FormRequest
{
    #[Override]
    protected function prepareForValidation(): void
    {
        if (auth('client')->check()) {
            $this->merge([
                'client_id' => auth('client')->id(),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'content'   => ['required', 'string'],
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
            'content' => __('community::attribute.content'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'client_id.required' => __('community::message.client_id_required'),
            'client_id.integer'  => __('community::message.client_id_integer'),
            'client_id.exists'   => __('community::message.client_id_exists'),
            'content.required'   => __('community::message.content_required'),
            'content.string'     => __('community::message.content_string'),
        ];
    }
}
