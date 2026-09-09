<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ClientEditProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $clientId = $this->user('client')?->id;

        return [
            'name'                      => ['required', 'string', 'max:255'],
            'email'                     => ['nullable', 'email', 'max:255', 'unique:clients,email,' . $clientId],
            'phone'                     => ['required', 'string', 'max:20', 'unique:clients,phone,' . $clientId],
            'old_password'              => ['nullable', 'string', 'required_with:new_password', 'current_password:client'],
            'new_password'              => ['nullable', 'string', 'confirmed', 'required_with:old_password'],
            'new_password_confirmation' => ['nullable', 'string'],
            'image'                     => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:2048'],
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
            'name'                      => __('client::attribute.name'),
            'email'                     => __('client::attribute.email'),
            'phone'                     => __('client::attribute.phone'),
            'old_password'              => __('client::attribute.old_password'),
            'new_password'              => __('client::attribute.new_password'),
            'new_password_confirmation' => __('client::attribute.new_password_confirmation'),
            'image'                     => __('client::attribute.image'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'name.required'               => __('client::message.name_required'),
            'name.string'                 => __('client::message.name_string'),

            'email.email'                 => __('client::message.email_email'),
            'email.unique'                => __('client::message.email_unique'),

            'phone.required'              => __('client::message.phone_required'),
            'phone.string'                => __('client::message.phone_string'),
            'phone.unique'                => __('client::message.phone_unique'),

            'old_password.required_with'  => __('client::message.old_password_required_with'),
            'old_password.string'         => __('client::message.password_string'),
            'old_password.current_password' => __('client::message.old_password_incorrect'),

            'new_password.string'         => __('client::message.password_string'),
            'new_password.confirmed'      => __('client::message.password_confirmed'),
            'new_password.required_with'  => __('client::message.new_password_required_with'),

            'image.image'                 => __('client::message.image_invalid'),
            'image.mimes'                 => __('client::message.image_mimes'),
            'image.max'                   => __('client::message.image_max'),
        ];
    }
}
