<?php

namespace Modules\Pet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (auth('client')->check()) {
            $this->merge([
                'client_id' => auth('client')->id(),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'client_id'   => ['required', 'integer', 'exists:clients,id'],
            'pet_type_id' => ['required', 'integer', 'exists:pet_types,id'],
            'name_en'     => ['required', 'string', 'max:255'],
            'name_ar'     => ['required', 'string', 'max:255'],
            'breed_en'    => ['nullable', 'string', 'max:255'],
            'breed_ar'    => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'weight'      => ['nullable', 'numeric', 'min:0'],
            'gender'      => ['nullable', 'string', 'in:m,f'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:2048'],
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
            'client_id'   => __('pet::attribute.client_id'),
            'pet_type_id' => __('pet::attribute.pet_type_id'),
            'name_en'     => __('pet::attribute.name_en'),
            'name_ar'     => __('pet::attribute.name_ar'),
            'breed_en'    => __('pet::attribute.breed_en'),
            'breed_ar'    => __('pet::attribute.breed_ar'),
            'date_of_birth' => __('pet::attribute.date_of_birth'),
            'weight'      => __('pet::attribute.weight'),
            'gender'      => __('pet::attribute.gender'),
            'image'       => __('pet::attribute.image'),
        ];
    }

    #[\Override]
    public function messages()
    {
        return [
            'client_id.required' => __('pet::message.client_id_required'),
            'client_id.integer'  => __('pet::message.client_id_integer'),
            'client_id.exists'   => __('pet::message.client_id_exists'),

            'pet_type_id.required' => __('pet::message.pet_type_id_required'),
            'pet_type_id.integer'  => __('pet::message.pet_type_id_integer'),
            'pet_type_id.exists'   => __('pet::message.pet_type_id_exists'),

            'name_en.required' => __('pet::message.name_en_required'),
            'name_en.string'   => __('pet::message.name_en_string'),
            'name_en.max'      => __('pet::message.name_en_max'),

            'name_ar.required' => __('pet::message.name_ar_required'),
            'name_ar.string'   => __('pet::message.name_ar_string'),
            'name_ar.max'      => __('pet::message.name_ar_max'),

            'breed_en.string' => __('pet::message.breed_en_string'),
            'breed_en.max'    => __('pet::message.breed_en_max'),

            'breed_ar.string' => __('pet::message.breed_ar_string'),
            'breed_ar.max'    => __('pet::message.breed_ar_max'),

            'date_of_birth.date' => __('pet::message.date_of_birth_date'),

            'weight.numeric' => __('pet::message.weight_numeric'),
            'weight.min'     => __('pet::message.weight_min'),

            'gender.string' => __('pet::message.gender_string'),
            'gender.in'     => __('pet::message.gender_in'),

            'image.image' => __('pet::message.image_invalid'),
            'image.mimes' => __('pet::message.image_mimes'),
            'image.max'   => __('pet::message.image_max'),
        ];
    }
}
