<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ClientLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string'],
            'password' => ['required', 'string'],
            'fcm_token' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    #[Override]
    public function attributes()
    {
        return [
            'phone' => __('client::attribute.phone'),
            'password' => __('client::attribute.password'),
            'fcm_token' => __('client::attribute.fcm_token'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'phone.required' => __('client::message.phone_required'),
            'phone.string' => __('client::message.phone_string'),
            'password.required' => __('client::message.password_required'),
            'password.string' => __('client::message.password_string'),
        ];
    }
}
