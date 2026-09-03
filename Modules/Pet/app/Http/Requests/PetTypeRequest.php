<?php

namespace Modules\Pet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title_en'  => ['required', 'string', 'max:255'],
            'title_ar'  => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    #[\Override]
    public function attributes()
    {
        return [
            'title_en'  => __('pet::attribute.title_en'),
            'title_ar'  => __('pet::attribute.title_ar'),
            'is_active' => __('pet::attribute.is_active'),
        ];
    }

    #[\Override]
    public function messages()
    {
        return [
            'title_en.required' => __('pet::message.title_en_required'),
            'title_en.string'   => __('pet::message.title_en_string'),
            'title_en.max'      => __('pet::message.title_en_max'),

            'title_ar.required' => __('pet::message.title_ar_required'),
            'title_ar.string'   => __('pet::message.title_ar_string'),
            'title_ar.max'      => __('pet::message.title_ar_max'),

            'is_active.boolean' => __('pet::message.is_active_boolean'),
        ];
    }
}
