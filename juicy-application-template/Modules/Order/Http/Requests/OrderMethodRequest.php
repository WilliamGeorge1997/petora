<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {

        $rules = [];

        if (!empty(\Request::segment(3))) {

            $rules += ['title_ar' => ['required', 'max:191']];

            $rules += ['title_en' => ['required', 'max:191']];
        } else {
            $rules += ['title_ar' => ['required', 'max:191']];

            $rules += ['title_en' => ['required', 'max:191']];

            $rules += ['image' => ['required']];
        }

        return $rules;
    }

    public function messages()
    {

        return [

            'title_ar.required' => 'العنوان مطلوب AR ',
            'title_en.required' => ' العنوان مطلوب EN',
            'image.required' => ' الصورة مطلوبة',

        ];
    }
}
