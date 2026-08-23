<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AddonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title_ar' => 'required|max:191',
            'title_en' => 'required|max:191',
            'display_ar' => 'nullable|max:191',
            'display_en' => 'nullable|max:191',
            'addon_values' => 'required|array',
            'addon_values.*.value_ar' => 'required|max:191',
            'addon_values.*.value_en' => 'required|max:191',
            'addon_values.*.price' => 'required|numeric',
            'addon_values.*.image' => $this->isMethod('put') ? 'nullable' : 'required|image|max:2048',
            'branch_id' => auth()->guard('admin')->user()->hasRole('Super Admin') ? 'required|exists:branches,id' : 'prohibited',
        ];
    }
    public function attributes()
    {
        return [
            'title_ar' => 'الاسم باللغة العربية',
            'title_en' => 'الاسم باللغة الانجليزية',
            'display_ar' => 'اسم العرض في لوحه التحكم باللغه العربية',
            'display_en' => 'اسم العرض في لوحه التحكم باللغه الانجليزية',
            'branch_id' => 'الفرع',
            'addon_values' => 'قيم الخاصية',
            'addon_values.*.value_ar' => 'قيمة الخاصية باللغة العربية',
            'addon_values.*.value_en' => 'قيمة الخاصية باللغة الانجليزية',
            'addon_values.*.price' => 'السعر',
            'addon_values.*.image' => 'الصورة',
            'branch_id.required' => 'الفرع مطلوب',
            'branch_id.exists' => 'الفرع غير موجود',
            'branch_id.prohibited' => 'لا يمكنك تعديل الفرع',
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
    public function messages()
    {
        return [
            'addon_values.required' => 'يرجى إضافة قيم الخاصية.',
            'addon_values.*.value_ar.required' => 'حقل قيمة الخاصية باللغة العربية مطلوب.',
            'addon_values.*.value_en.required' => 'حقل قيمة الخاصية باللغة الانجليزية مطلوب.',
            'addon_values.*.price.required' => 'حقل السعر مطلوب.',
            'addon_values.*.price.numeric' => 'حقل السعر يجب أن يكون رقمًا.',
            'addon_values.*.image.required' => 'حقل الصورة مطلوب.',
            'addon_values.*.image.image' => 'الصورة يجب أن تكون ملف صورة صالح.',
            'display_ar.required' => 'حقل اسم العرض في لوحه التحكم باللغه العربية مطلوب.',
            'display_en.required' => 'حقل اسم العرض في لوحه التحكم باللغه الانجليزية مطلوب.',
        ];
    }
    public function passedValidation()
    {
        $this->merge([
            'title' => ['en' => $this->title_en, 'ar' => $this->title_ar],
            'is_active' => isset($this->is_active) ? 1 : 0,
            'multi_select' => isset($this->multi_select) ? 1 : 0,
            'display' => ['en' => $this->display_en, 'ar' => $this->display_ar],
        ]);
    }
}
