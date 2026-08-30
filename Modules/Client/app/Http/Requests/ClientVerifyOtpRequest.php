<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ClientVerifyOtpRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone'     => ['required', 'string', 'exists:clients,phone'],
            'otp'       => ['required', 'numeric'],
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
            'phone'     => __('client::attribute.phone'),
            'otp'       => __('client::attribute.otp'),
            'fcm_token' => __('client::attribute.fcm_token'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'phone.required'    => __('client::message.phone_required'),
            'phone.string'      => __('client::message.phone_string'),
            'phone.exists'      => __('client::message.phone_not_found'),
            'otp.required'      => __('client::message.otp_required'),
            'otp.numeric'       => __('client::message.otp_numeric'),
        ];
    }
}
