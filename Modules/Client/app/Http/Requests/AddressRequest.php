<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class AddressRequest extends FormRequest
{
    protected function prepareForValidation()
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
            'title' => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'exists:countries,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'zone_id' => ['required', 'exists:zones,id'],
            'block' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'house_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'latitude' => ['nullable', 'string', 'max:255'],
            'longitude' => ['nullable', 'string', 'max:255'],
            'is_default' => ['nullable', 'boolean'],
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
            'client_id' => __('client::attribute.client_id'),
            'title' => __('client::attribute.title'),
            'country_id' => __('client::attribute.country_id'),
            'city_id' => __('client::attribute.city_id'),
            'zone_id' => __('client::attribute.zone_id'),
            'block' => __('client::attribute.block'),
            'street' => __('client::attribute.street'),
            'house_number' => __('client::attribute.house_number'),
            'notes' => __('client::attribute.notes'),
            'latitude' => __('client::attribute.latitude'),
            'longitude' => __('client::attribute.longitude'),
            'is_default' => __('client::attribute.is_default'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'client_id.required' => __('client::message.client_id_required'),
            'client_id.integer' => __('client::message.client_id_integer'),
            'client_id.exists' => __('client::message.client_id_exists'),

            'title.required' => __('client::message.title_required'),
            'title.string' => __('client::message.title_string'),
            'title.max' => __('client::message.title_max'),

            'country_id.required' => __('client::message.country_id_required'),
            'country_id.exists' => __('client::message.country_id_exists'),

            'city_id.required' => __('client::message.city_id_required'),
            'city_id.exists' => __('client::message.city_id_exists'),

            'zone_id.required' => __('client::message.zone_id_required'),
            'zone_id.exists' => __('client::message.zone_id_exists'),

            'block.string' => __('client::message.block_string'),
            'block.max' => __('client::message.block_max'),

            'street.string' => __('client::message.street_string'),
            'street.max' => __('client::message.street_max'),

            'house_number.string' => __('client::message.house_number_string'),
            'house_number.max' => __('client::message.house_number_max'),

            'notes.string' => __('client::message.notes_string'),
            'notes.max' => __('client::message.notes_max'),

            'latitude.string' => __('client::message.latitude_string'),
            'latitude.max' => __('client::message.latitude_max'),

            'longitude.string' => __('client::message.longitude_string'),
            'longitude.max' => __('client::message.longitude_max'),

            'is_default.boolean' => __('client::message.is_default_boolean'),
        ];
    }
}
