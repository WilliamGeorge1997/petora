<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ClientNewPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'exists:clients,phone'],
            'password' => ['required', 'string', 'confirmed'],
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
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'phone.required' => __('client::message.phone_required'),
            'phone.string' => __('client::message.phone_string'),
            'phone.exists' => __('client::message.phone_not_found'),
            'password.required' => __('client::message.password_required'),
            'password.string' => __('client::message.password_string'),
            'password.confirmed' => __('client::message.password_confirmed'),
        ];
    }
}
