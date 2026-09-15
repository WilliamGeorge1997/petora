<?php

namespace Modules\Client\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class FavouriteRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'store_id' => ['required_without:clinic_id', 'nullable', 'integer', 'exists:stores,id', 'prohibits:clinic_id'],
            'clinic_id' => ['required_without:store_id', 'nullable', 'integer', 'exists:clinics,id', 'prohibits:store_id'],
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
            'product_id' => __('client::attribute.product_id'),
            'store_id' => __('client::attribute.store_id'),
            'clinic_id' => __('client::attribute.clinic_id'),
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'product_id.required' => __('client::message.product_id_required'),
            'product_id.integer' => __('client::message.product_id_integer'),
            'product_id.exists' => __('client::message.product_id_exists'),

            'store_id.required_without' => __('client::message.store_or_clinic_required'),
            'store_id.prohibits' => __('client::message.store_and_clinic_prohibited'),
            'store_id.integer' => __('client::message.store_id_integer'),
            'store_id.exists' => __('client::message.store_id_exists'),

            'clinic_id.required_without' => __('client::message.store_or_clinic_required'),
            'clinic_id.prohibits' => __('client::message.store_and_clinic_prohibited'),
            'clinic_id.integer' => __('client::message.clinic_id_integer'),
            'clinic_id.exists' => __('client::message.clinic_id_exists'),
        ];
    }
}
