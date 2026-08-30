<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ClientRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['nullable', 'email', 'unique:clients,email'],
            'phone'     => ['required', 'string', 'unique:clients,phone'],
            'password'  => ['required', 'string', 'confirmed'],
            'image'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:2048'],
            'fcm_token' => ['nullable', 'string'],
            'locale'      => ['nullable', 'string', 'in:en,ar'],
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
            'name'      => __('client::attribute.name'),
            'email'     => __('client::attribute.email'),
            'phone'     => __('client::attribute.phone'),
            'password'              => __('client::attribute.password'),
            'password_confirmation' => __('client::attribute.password_confirmation'),
            'image'                 => __('client::attribute.image'),
            'fcm_token'             => __('client::attribute.fcm_token'),
            'locale'      => __('client::attribute.locale'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'name.required'     => __('client::message.name_required'),
            'name.string'       => __('client::message.name_string'),
            'email.email'       => __('client::message.email_email'),
            'email.unique'      => __('client::message.email_unique'),
            'phone.required'    => __('client::message.phone_required'),
            'phone.string'      => __('client::message.phone_string'),
            'phone.unique'      => __('client::message.phone_unique'),
            'password.required'  => __('client::message.password_required'),
            'password.min'       => __('client::message.password_min'),
            'password.confirmed' => __('client::message.password_confirmed'),
            'image.image'        => __('client::message.image_invalid'),
            'image.mimes'       => __('client::message.image_mimes'),
            'image.max'         => __('client::message.image_max'),
        ];
    }


}
