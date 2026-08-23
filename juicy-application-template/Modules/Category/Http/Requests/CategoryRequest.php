<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (Request::isMethod('post'))
            {
             return [
                    'title_ar'=>'required|max:191',
                    'title_en'=>'required|max:191',
                    'image'=>'required',
                    'sort_order'=>'required',
                ];
            }
        else{
            return [
                'title_ar'=>'required|max:191',
                'title_en'=>'required|max:191',
                'sort_order'=>'required',
            ];

        }

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
