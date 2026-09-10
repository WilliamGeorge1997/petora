<?php

namespace Modules\Community\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoryRequest extends FormRequest
{
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
            'client_id'  => ['required', 'integer', 'exists:clients,id'],
            'media'      => ['required', 'file', 'mimes:jpeg,png,jpg,webp,mp4,mov', 'max:2048'],
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
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'client_id.required' => __('community::message.client_id_required'),
            'client_id.integer' => __('community::message.client_id_integer'),
            'client_id.exists' => __('community::message.client_id_exists'),

            'media.required' => __('community::message.media_required'),
            'media.mimes' => __('community::message.media_mimes'),
            'media.max' => __('community::message.media_max'),
        ];
    }
}