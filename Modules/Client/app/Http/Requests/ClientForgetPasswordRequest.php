<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ClientForgetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'exists:clients,phone'],
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
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'phone.required' => __('client::message.phone_required'),
            'phone.string' => __('client::message.phone_string'),
            'phone.exists' => __('client::message.phone_not_found'),
        ];
    }
}
