<?php

namespace Modules\Branch\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class RegistrationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_ar' => ['required', 'string'],
            'name_en' => ['required', 'string'],
            'address_ar' => ['required', 'string'],
            'address_en' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'theme' => ['required', 'string'],
            'is_order_enabled' => ['nullable', 'in:1,0'],
            'manager_name' => ['required', 'string'],
            'manager_phone' => ['required', 'string'],
            'location_url' => ['required', 'url'],
            'working_from' => ['required', 'string'],
            'working_to' => ['required', 'string'],
            'is_working_24_hours' => ['nullable', 'in:1,0'],
            'image' => ['required', 'image', 'max:2048'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            return_msg(false, 'Validation failed', ['errors' => $validator->errors()], 'validation_error')
        );
    }
}
