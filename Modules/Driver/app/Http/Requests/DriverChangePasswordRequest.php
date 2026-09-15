<?php

namespace Modules\Driver\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class DriverChangePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'old_password' => ['required', 'string', 'current_password:driver'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
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
            'old_password' => __('driver::attribute.old_password'),
            'new_password' => __('driver::attribute.new_password'),
            'new_password_confirmation' => __('driver::attribute.new_password_confirmation'),
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'old_password.required' => __('driver::message.password_required'),
            'old_password.current_password' => __('driver::message.wrong_old_password'),
            'new_password.required' => __('driver::message.password_required'),
            'new_password.min' => __('driver::message.password_min'),
            'new_password.confirmed' => __('driver::message.password_confirmed'),
        ];
    }
}
