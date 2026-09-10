<?php

namespace Modules\Community\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class BlockRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'blocker_id' => ['required', 'integer', 'exists:clients,id'],
            'blocked_id' => ['required', 'integer', 'exists:clients,id'],
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
            'blocker_id' => __('community::attribute.blocker_id'),
            'blocked_id' => __('community::attribute.blocked_id'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'blocker_id.required' => __('community::message.blocker_id_required'),
            'blocker_id.integer' => __('community::message.blocker_id_integer'),
            'blocker_id.exists' => __('community::message.blocker_id_exists'),
            'blocked_id.required' => __('community::message.blocked_id_required'),
            'blocked_id.integer' => __('community::message.blocked_id_integer'),
            'blocked_id.exists' => __('community::message.blocked_id_exists'),
        ];
    }
}
