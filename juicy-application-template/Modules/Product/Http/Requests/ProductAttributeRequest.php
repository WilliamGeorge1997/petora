<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductAttributeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attribute_ar' => 'required|max:191',
            'attribute_en' => 'required|max:191',
            'product_id' => 'required|exists:products,id',
            'attribute_values.*.image' => $this->isMethod('POST') ? 'required|image' : 'nullable',
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
}
