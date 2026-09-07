<?php

namespace Modules\Community\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class LikeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'client_id'     => ['required', 'integer', 'exists:clients,id'],
            'likeable_id'   => ['required', 'integer'],
            'likeable_type' => ['required', 'string'],
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
            'likeable_id' => __('community::attribute.likeable_id'),
            'likeable_type' => __('community::attribute.likeable_type'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'client_id.required' => __('community::message.client_id_required'),
            'likeable_id.required' => __('community::message.likeable_id_required'),
            'likeable_type.required' => __('community::message.likeable_type_required'),
        ];
    }
}