<?php

namespace Modules\Driver\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class DriverLoginRequest extends FormRequest
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
    public function attributes(): array
    {
        return [
            'phone' => __('driver::attribute.phone'),
            'password' => __('driver::attribute.password'),
            'fcm_token' => __('driver::attribute.fcm_token'),
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'phone.required' => __('driver::message.phone_required'),
            'phone.string' => __('driver::message.phone_string'),
            'password.required' => __('driver::message.password_required'),
            'password.string' => __('driver::message.password_string'),
        ];
    }
}
