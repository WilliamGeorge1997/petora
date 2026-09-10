<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class AdminLoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:admins,email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    #[Override]
    public function messages(): array
    {
        return [
            'email.required' => __('admin::admin.email_required'),
            'email.email' => __('admin::admin.email_email'),
            'email.exists' => __('admin::admin.email_exists'),
            'password.required' => __('admin::admin.password_required'),
            'password.string' => __('admin::admin.password_string'),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'email' => __('admin::admin.email'),
            'password' => __('admin::admin.password'),
        ];
    }
}
