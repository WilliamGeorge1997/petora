<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Modules\Product\Entities\ProductImage;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        if (Request::isMethod('post')) {
            $rules = [
                'title_ar' => 'required|max:191',
                'title_en' => 'required|max:191',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric',
                'slider_image' => 'required_if:is_slider,==,1',
                'sort_order' => 'nullable|integer',
                // 'types' => 'nullable|array',
                // 'types.*.title_en' => 'required_with:types|string|max:191',
                // 'types.*.title_ar' => 'required_with:types|string|max:191',
                // 'types.*.price' => 'required_with:types|numeric|min:0',
                // 'types.*.image' => 'nullable|image|max:2048',
                // 'types.*.sort_order' => 'nullable|integer',
                // 'types.*.is_active' => 'nullable|boolean',
                'sizes' => 'required_if:has_size,1|array',
                'sizes.*.name_ar' => 'required_with:sizes|string|max:191',
                'sizes.*.name_en' => 'required_with:sizes|string|max:191',
                'sizes.*.price' => 'required_with:sizes|numeric',
                'images' => 'nullable|array',
                // 'images.*' => 'required_with:images|image|max:512',
                'branch_id' => auth()->guard('admin')->user()->hasRole('Super Admin') ? 'required|exists:branches,id' : 'prohibited',
            ];
        } else {
            $rules = [
                'title_ar' => 'required|max:191',
                'title_en' => 'required|max:191',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric',
                'sort_order' => 'nullable|integer',
                // 'types' => 'nullable|array',
                // 'types.*.id' => 'nullable|exists:product_types,id',
                // 'types.*.title_en' => 'required_with:types|string|max:191',
                // 'types.*.title_ar' => 'required_with:types|string|max:191',
                // 'types.*.price' => 'required_with:types|numeric|min:0',
                // 'types.*.image' => 'nullable|image|max:2048',
                // 'types.*.sort_order' => 'nullable|integer',
                // 'types.*.is_active' => 'nullable|boolean',
                // 'sizes' => 'required_if:has_size,1|array',
                // 'sizes.*.name_ar' => 'required_with:sizes|string|max:191',
                // 'sizes.*.name_en' => 'required_with:sizes|string|max:191',
                // 'sizes.*.price' => 'required_with:sizes|numeric',
                // 'images.*' => 'required_with:images|image|max:512',
                'branch_id' => auth()->guard('admin')->user()->hasRole('Super Admin') ? 'required|exists:branches,id' : 'prohibited',
            ];

            if (ProductImage::whereProductId($this->route('product'))->count() == 0) {
                $rules += ['images' => 'required'];
            }
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'slider_image.required_if' => 'رجاء ادخال صورة السلايد عند اختيار عرض فى معرض الصور',
            'sizes.required_if' => 'يجب إضافة حجم واحد على الأقل عند اختيار المنتج له حجم',
            'sizes.*.name_ar.required_with' => 'الاسم بالعربية مطلوب',
            'sizes.*.name_en.required_with' => 'الاسم بالإنجليزية مطلوب',
            'sizes.*.price.required_with' => 'السعر مطلوب',
            'images.*.required_with' => 'الصورة مطلوبة',
            'images.*.image' => 'يجب أن يكون الملف صورة',
            'images.*.max' => 'يجب أن لا يتجاوز حجم الصورة 512 كيلوبايت',
        ];
    }

    public function authorize()
    {
        return true;
    }
}
