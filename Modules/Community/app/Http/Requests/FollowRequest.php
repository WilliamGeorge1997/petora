<?php

namespace Modules\Community\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class FollowRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'follower_id' => ['required', 'integer', 'exists:clients,id'],
            'following_id' => ['required', 'integer', 'exists:clients,id'],
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
            'follower_id' => __('community::attribute.follower_id'),
            'following_id' => __('community::attribute.following_id'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'follower_id.required' => __('community::message.follower_id_required'),
            'follower_id.integer' => __('community::message.follower_id_integer'),
            'follower_id.exists' => __('community::message.follower_id_exists'),
            'following_id.required' => __('community::message.following_id_required'),
            'following_id.integer' => __('community::message.following_id_integer'),
            'following_id.exists' => __('community::message.following_id_exists'),
        ];
    }
}
