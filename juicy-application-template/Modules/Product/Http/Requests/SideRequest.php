<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SideRequest extends FormRequest
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
            'max_selection' => 'required|integer',
            'side_values' => 'required|array',
            'side_values.*.value_ar' => 'required|max:191',
            'side_values.*.value_en' => 'required|max:191',
            'side_values.*.image' => $this->isMethod('put') ? 'nullable' : 'required|image|max:2048',
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
            'max_selection.required' => 'حقل اقصي عدد للاختيارات مطلوب',
            'max_selection.integer' => 'حقل اقصي عدد للاختيارات يجب ان يكون رقم صحيح',
            'branch_id' => 'الفرع',
            'side_values' => 'قيم الاطباق الجانبية',
            'side_values.*.value_ar' => 'قيمة الطبق الجانبي باللغة العربية',
            'side_values.*.value_en' => 'قيمة الطبق الجانبي باللغة الانجليزية',
            'side_values.*.price' => 'السعر',
            'side_values.*.image' => 'الصورة',
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
            'side_values.required' => 'يرجى إضافة قيم الاطباق الجانبية.',
            'side_values.*.value_ar.required' => 'حقل قيمة الطبق الجانبي باللغة العربية مطلوب.',
            'side_values.*.value_en.required' => 'حقل قيمة الطبق الجانبي باللغة الانجليزية مطلوب.',
            'display_ar.required' => 'حقل اسم العرض في لوحه التحكم باللغه العربية مطلوب.',
            'display_en.required' => 'حقل اسم العرض في لوحه التحكم باللغه الانجليزية مطلوب.',
            'side_values.*.price.required' => 'حقل السعر مطلوب.',
            'side_values.*.price.numeric' => 'حقل السعر يجب أن يكون رقمًا.',
            'side_values.*.image.required' => 'حقل الصورة مطلوب.',
            'side_values.*.image.image' => 'الصورة يجب أن تكون ملف صورة صالح.',
        ];
    }
    public function passedValidation()
    {
        $this->merge([
            'title' => ['en' => $this->title_en, 'ar' => $this->title_ar],
            'is_active' => isset($this->is_active) ? 1 : 0,
            'display' => ['en' => $this->display_en, 'ar' => $this->display_ar],
        ]);
    }
}
