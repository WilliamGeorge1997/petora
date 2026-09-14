<?php

namespace Modules\Driver\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Driver\Models\Driver;
use Override;

class DriverRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $driver = $this->route('driver');
        $driverId = $driver instanceof Driver ? $driver->id : $driver;

        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:drivers,phone,' . $driverId],
            'password' => $driverId ? ['nullable', 'string', 'min:6'] : ['required', 'string', 'min:6'],
            'license_id' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg,gif', 'max:2048'],
            'store_id' => ['nullable', 'integer', 'exists:stores,id'],
            'clinic_id' => ['nullable', 'integer', 'exists:clinics,id'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'city_id' => ['nullable', 'integer', 'exists:cities,id'],
            'zone_id' => ['nullable', 'integer', 'exists:zones,id'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'locale' => ['required', 'string', 'in:en,ar'],
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
    public function attributes(): array
    {
        return [
            'name' => __('driver::attribute.name'),
            'phone' => __('driver::attribute.phone'),
            'password' => __('driver::attribute.password'),
            'license_id' => __('driver::attribute.license_id'),
            'image' => __('driver::attribute.image'),
            'store_id' => __('driver::attribute.store_id'),
            'clinic_id' => __('driver::attribute.clinic_id'),
            'country_id' => __('driver::attribute.country_id'),
            'city_id' => __('driver::attribute.city_id'),
            'zone_id' => __('driver::attribute.zone_id'),
            'latitude' => __('driver::attribute.latitude'),
            'longitude' => __('driver::attribute.longitude'),
            'locale' => __('driver::attribute.locale'),
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'name.required' => __('driver::message.name_required'),
            'phone.required' => __('driver::message.phone_required'),
            'phone.unique' => __('driver::message.phone_unique'),
            'password.required' => __('driver::message.password_required'),
            'password.min' => __('driver::message.password_min'),
            'image.image' => __('driver::message.image_invalid'),
            'image.mimes' => __('driver::message.image_mimes'),
            'image.max' => __('driver::message.image_max'),
            'store_id.exists' => __('driver::message.store_id_exists'),
            'clinic_id.exists' => __('driver::message.clinic_id_exists'),
            'country_id.exists' => __('driver::message.country_id_exists'),
            'city_id.exists' => __('driver::message.city_id_exists'),
            'zone_id.exists' => __('driver::message.zone_id_exists'),
        ];
    }
}
