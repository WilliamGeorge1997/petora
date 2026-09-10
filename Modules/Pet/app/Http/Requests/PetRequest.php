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
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'pet_type_id' => ['required', 'integer', 'exists:pet_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'breed' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'gender' => ['required', 'string', 'in:m,f'],
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

    #[\Override]
    public function attributes()
    {
        return [
            'client_id' => __('pet::attribute.client_id'),
            'pet_type_id' => __('pet::attribute.pet_type_id'),
            'name' => __('pet::attribute.name'),
            'breed' => __('pet::attribute.breed'),
            'date_of_birth' => __('pet::attribute.date_of_birth'),
            'weight' => __('pet::attribute.weight'),
            'gender' => __('pet::attribute.gender'),
            'image' => __('pet::attribute.image'),
        ];
    }

    #[\Override]
    public function messages()
    {
        return [
            'client_id.required' => __('pet::message.client_id_required'),
            'client_id.integer' => __('pet::message.client_id_integer'),
            'client_id.exists' => __('pet::message.client_id_exists'),

            'pet_type_id.required' => __('pet::message.pet_type_id_required'),
            'pet_type_id.integer' => __('pet::message.pet_type_id_integer'),
            'pet_type_id.exists' => __('pet::message.pet_type_id_exists'),

            'name.required' => __('pet::message.name_required'),
            'name.string' => __('pet::message.name_string'),
            'name.max' => __('pet::message.name_max'),

            'breed.string' => __('pet::message.breed_string'),
            'breed.max' => __('pet::message.breed_max'),

            'date_of_birth.date' => __('pet::message.date_of_birth_date'),

            'weight.numeric' => __('pet::message.weight_numeric'),
            'weight.min' => __('pet::message.weight_min'),

            'gender.required' => __('pet::message.gender_required'),
            'gender.string' => __('pet::message.gender_string'),
            'gender.in' => __('pet::message.gender_in'),

            'image.image' => __('pet::message.image_invalid'),
            'image.mimes' => __('pet::message.image_mimes'),
            'image.max' => __('pet::message.image_max'),
        ];
    }
}
