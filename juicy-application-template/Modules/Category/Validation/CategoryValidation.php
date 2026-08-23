<?php


namespace Modules\Category\Validation;


trait CategoryValidation
{
    protected function validateStore($data)
    {
        return validator(
            $data,
            [
                'title_ar' => 'required|max:191',
                'title_en' => 'required|max:191',
                'image' => 'required',
                'category_id' => 'sometimes|nullable|exists:categories,id',
                'branch_id' => auth()->guard('admin')->user()->hasRole('Super Admin') ? 'required|exists:branches,id' : 'prohibited',
            ],
            [
                'branch_id.required' => 'الفرع مطلوب',
                'branch_id.exists' => 'الفرع غير موجود',
                'branch_id.prohibited' => 'لا يمكنك تعديل الفرع',
                'title_ar.required' => 'الاسم باللغة العربية مطلوب',
                'title_en.required' => 'الاسم باللغة الانجليزية مطلوب',
                'image.required' => 'الصورة مطلوبة',
                'category_id.exists' => 'القسم غير موجودة',
                'category_id.sometimes' => 'القسم مطلوبة',
                'category_id.nullable' => 'القسم مطلوبة',
                'category_id.exists' => 'القسم غير موجودة',
            ]
        );
    }

    protected function validateUpdate($data)
    {
        return validator(
            $data,
            [
                'title_ar' => 'required|max:191',
                'title_en' => 'required|max:191',
                'category_id' => 'sometimes|nullable|exists:categories,id',
                'branch_id' => auth()->guard('admin')->user()->hasRole('Super Admin') ? 'required|exists:branches,id' : 'prohibited',
            ],
            [
                'branch_id.required' => 'الفرع مطلوب',
                'branch_id.exists' => 'الفرع غير موجود',
                'branch_id.prohibited' => 'لا يمكنك تعديل الفرع',
                'title_ar.required' => 'الاسم باللغة العربية مطلوب',
                'title_en.required' => 'الاسم باللغة الانجليزية مطلوب',
                'image.required' => 'الصورة مطلوبة',
                'category_id.exists' => 'القسم غير موجودة',
                'category_id.sometimes' => 'القسم مطلوبة',
                'category_id.nullable' => 'القسم مطلوبة',
                'category_id.exists' => 'القسم غير موجودة',
            ]
        );
    }
}
