<?php

namespace Modules\Gallery\Validation;

trait GalleryValidation
{
    protected function validateStore($data)
    {
        return validator(
            $data,
            [
                'branch_id' => 'sometimes|exists:branches,id',
                'images'    => 'required|array|min:1',
                'images.*'  => 'required',
            ],
            [
                'branch_id.exists'   => 'الفرع المحدد غير موجود.',
                'images.required'    => 'يجب اختيار صورة واحدة على الأقل.',
                'images.array'       => 'يجب أن تكون الصور مصفوفة.',
                'images.min'         => 'يجب اختيار صورة واحدة على الأقل.',
                'images.*.required'  => 'يجب أن تكون كل صورة موجودة.',
            ]
        );
    }

    protected function validateUpdate($data)
    {
        return validator(
            $data,
            [
                'image'      => 'nullable',
                'sort_order' => 'required|integer|min:0',
            ],
            [
                'image.image'        => 'يجب أن يكون الملف صورة صالحة.',
                'image.mimes'        => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg, webp, bmp.',
                'image.max'          => 'يجب أن لا يتجاوز حجم الصورة 2048 كيلوبايت.',
                'sort_order.integer' => 'يجب أن يكون الترتيب رقماً صحيحاً.',
                'sort_order.min'     => 'يجب أن يكون الترتيب أكبر من أو يساوي 0.',
            ]
        );
    }
}
