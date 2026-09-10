<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ClientRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $clientId = $this->route('client') ? $this->route('client')->id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:clients,email,'.$clientId],
            'phone' => ['required', 'string', 'max:20', 'unique:clients,phone,'.$clientId],
            'password' => $clientId ? ['nullable', 'string'] : ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:2048'],
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
            'name' => __('client::attribute.name'),
            'email' => __('client::attribute.email'),
            'phone' => __('client::attribute.phone'),
            'password' => __('client::attribute.password'),
            'image' => __('client::attribute.image'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'name.required' => __('client::message.name_required'),
            'name.string' => __('client::message.name_string'),

            'email.email' => __('client::message.email_email'),
            'email.unique' => __('client::message.email_unique'),

            'phone.required' => __('client::message.phone_required'),
            'phone.string' => __('client::message.phone_string'),
            'phone.unique' => __('client::message.phone_unique'),

            'password.required' => __('client::message.password_required'),
            'password.string' => __('client::message.password_string'),

            'image.image' => __('client::message.image_invalid'),
            'image.mimes' => __('client::message.image_mimes'),
            'image.max' => __('client::message.image_max'),
        ];
    }
}
