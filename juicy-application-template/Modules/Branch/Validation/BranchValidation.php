<?php


namespace Modules\Branch\Validation;


trait BranchValidation
{
    protected function validateStore($data)
    {
        return validator(
            $data,
            [
                'title_ar' => 'required|max:191',
                'title_en' => 'required|max:191',
                'address_ar' => 'nullable|max:191',
                'address_en' => 'nullable|max:191',
                'city_ar' => 'nullable|max:191',
                'city_en' => 'nullable|max:191',
                'manager_name' => 'required|max:255',
                'manager_phone' => 'required|max:255',
                'manager_email' => 'required|email|max:255|unique:admins,email',
                'manager_password' => 'required|max:255',
                'manager_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,bmp',
                'delivery_fee_fixed' => 'nullable|numeric',
                'delivery_fee_per_km' => 'nullable|numeric',
                'delivery_areas' => 'nullable|array',
                'delivery_areas.*.title' => 'nullable|string|max:191',
                'delivery_areas.*.price' => 'nullable|numeric',
                'working_hours' => 'required|array',
                'working_hours.*.day' => 'required|string',
                'working_hours.*.from' => 'required',
                'working_hours.*.to' => 'required',
            ],
        );
    }

    protected function validateUpdate($data)
    {
        return validator(
            $data,
            [
                'title_ar' => 'required|max:191',
                'title_en' => 'required|max:191',
                'city_ar' => 'nullable|max:191',
                'city_en' => 'nullable|max:191',
                'delivery_fee_fixed' => 'nullable|numeric',
                'delivery_fee_per_km' => 'nullable|numeric',
                'delivery_areas' => 'nullable|array',
                'delivery_areas.*.title' => 'nullable|string|max:191',
                'delivery_areas.*.price' => 'nullable|numeric',
                'working_hours.*.day' => 'required|string',
                'working_hours.*.from' => 'required',
                'working_hours.*.to' => 'required',
            ],
        );
    }

    protected function validateSetting($data)
    {
        return validator(
            $data,
            [
                'currency_ar' => 'required|max:191',
                'currency_en' => 'required|max:191',
                'app_background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,bmp|max:1024',
                'header_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,bmp|max:1024',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,bmp|max:1024',
                'email' => 'nullable|email|max:255',
                'tax' => 'nullable|numeric|min:0',
                'service' => 'nullable|numeric|min:0',
            ],
            [
                'currency_ar.required' => 'حقل العملة بالعربية مطلوب.',
                'currency_en.required' => 'حقل العملة بالإنجليزية مطلوب.',
                'app_background_image.image' => 'يجب أن تكون صورة الخلفية الموجودة في التطبيق صورة صالحة.',
                'header_image.image' => 'يجب أن تكون صورة الرأسية الموجودة في التطبيق صورة صالحة.',
                'logo.image' => 'يجب أن تكون صورة الشعار الموجودة في التطبيق صورة صالحة.',
                'app_background_image.mimes' => 'يجب أن تكون صورة الخلفية من نوع: jpeg, png, jpg, webp, bmp.',
                'header_image.mimes' => 'يجب أن تكون صورة الرأسية من نوع: jpeg, png, jpg, webp, bmp.',
                'logo.mimes' => 'يجب أن تكون صورة الشعار من نوع: jpeg, png, jpg, webp, bmp.',
                'app_background_image.max' => 'يجب أن تكون حجم صورة الخلفية لا يزيد عن 1024 كيلوبايت.',
                'header_image.max' => 'يجب أن تكون حجم صورة الرأسية لا يزيد عن 1024 كيلوبايت.',
                'logo.max' => 'يجب أن تكون حجم صورة الشعار لا يزيد عن 1024 كيلوبايت.',
                'email.email' => 'يجب أن يكون البريد الإلكتروني صالحاً.',
                'tax.numeric' => 'يجب أن يكون حقل الضريبة رقماً.',
                'tax.min' => 'يجب ألا تقل قيمة الضريبة عن صفر.',
                'service.numeric' => 'يجب أن يكون حقل الخدمة رقماً.',
                'service.min' => 'يجب ألا تقل قيمة الخدمة عن صفر.',
            ]
        );
    }

    protected function validateUpdateOffer($data)
    {
        return validator(
            $data,
            [
                'branch_id' => 'required|exists:branches,id',
                'app_offer_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,bmp|max:2048',
                'app_offer_product_id' => 'nullable|integer|exists:products,id,branch_id,' . ($data['branch_id']),
                'app_offer_ends_at' => 'nullable'
            ],
            [
                'branch_id.required' => 'حقل الفرع مطلوب.',
                'branch_id.exists' => 'الفرع المحدد غير موجود.',
                'app_offer_image.image' => 'يجب أن تكون صورة العرض صورة صالحة.',
                'app_offer_image.mimes' => 'يجب أن تكون صورة العرض من نوع: jpeg, png, jpg, webp, bmp.',
                'app_offer_product_id.integer' => 'رقم المنتج يجب أن يكون رقماً صحيحاً.',
                'app_offer_product_id.exists' => 'المنتج المحدد غير موجود في هذا الفرع.',
                'app_offer_image.max' => 'يجب أن تكون حجم صورة العرض لا يزيد عن 1024 كيلوبايت.',
            ]
        );
    }
}
