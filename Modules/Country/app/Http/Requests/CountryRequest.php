<?php

namespace Modules\Country\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class CountryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title_en'   => ['required', 'string'],
            'title_ar'   => ['required', 'string'],
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
            'title_en'   => __('country::attribute.title_en'),
            'title_ar'   => __('country::attribute.title_ar'),
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'title_en.required' => __('country::message.title_en_required'),
            'title_en.string'   => __('country::message.title_en_string'),
            
            'title_ar.required' => __('country::message.title_ar_required'),
            'title_ar.string'   => __('country::message.title_ar_string'),
        ];
    }
}
