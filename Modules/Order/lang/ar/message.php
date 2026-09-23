<?php

return [
    'order' => [
        'fetched' => 'تم جلب الطلبات بنجاح.',
        'created' => 'تم إنشاء الطلب بنجاح.',
        'updated' => 'تم تحديث الطلب بنجاح.',
        'deleted' => 'تم حذف الطلب بنجاح.',
        'cancelled' => 'تم إلغاء الطلب بنجاح.',
        'cannot_cancel' => 'لا يمكن إلغاء الطلب في هذه المرحلة.',
    ],
    'order_method' => [
        'fetched' => 'تم جلب طرق الطلب بنجاح.',
    ],
    'payment_method' => [
        'fetched' => 'تم جلب طرق الدفع بنجاح.',
        'created' => 'تم إنشاء طريقة الدفع بنجاح.',
        'updated' => 'تم تحديث طريقة الدفع بنجاح.',
        'deleted' => 'تم حذف طريقة الدفع بنجاح.',
        'activated' => 'تم تفعيل طريقة الدفع بنجاح.',
        'deactivated' => 'تم إلغاء تفعيل طريقة الدفع بنجاح.',
    ],
    'order_status' => [
        'fetched' => 'تم جلب حالات الطلب بنجاح.',
    ],

    'title_en_required' => 'حقل الاسم باللغة الإنجليزية مطلوب.',
    'title_en_string' => 'تنسيق الاسم باللغة الإنجليزية غير صالح.',
    'title_en_max' => 'يجب ألا يتجاوز الاسم باللغة الإنجليزية 255 حرفاً.',

    'title_ar_required' => 'حقل الاسم باللغة العربية مطلوب.',
    'title_ar_string' => 'تنسيق الاسم باللغة العربية غير صالح.',
    'title_ar_max' => 'يجب ألا يتجاوز الاسم باللغة العربية 255 حرفاً.',

    'is_active_boolean' => 'قيمة التفعيل غير صالحة.',

    'order_method_id_required' => 'طريقة الطلب مطلوبة.',
    'payment_method_id_required' => 'طريقة الدفع مطلوبة.',
    'address_id_required' => 'العنوان مطلوب.',
    'address_id_exists' => 'العنوان المحدد غير صالح.',
    'items_required' => 'العناصر مطلوبة.',
    'items_array' => 'يجب أن تكون العناصر مصفوفة.',
    'product_id_required' => 'المنتج مطلوب.',
    'product_id_exists' => 'المنتج المحدد غير صالح.',
    'quantity_required' => 'الكمية مطلوبة.',
    'quantity_min' => 'يجب أن تكون الكمية 1 على الأقل.',
    'store_id_required_without' => 'المتجر مطلوب عندما لا تكون العيادة موجودة.',
    'store_id_exists' => 'المتجر المحدد غير صالح.',
    'store_id_prohibits' => 'لا يمكن اختيار المتجر والعيادة معاً.',
    'clinic_id_required_without' => 'العيادة مطلوبة عندما لا يكون المتجر موجوداً.',
    'clinic_id_exists' => 'العيادة المحددة غير صالحة.',
    'clinic_id_prohibits' => 'لا يمكن اختيار العيادة والمتجر معاً.',
    'delivery_date_required' => 'تاريخ التوصيل مطلوب.',
    'delivery_date_date' => 'يجب أن يكون تاريخ التوصيل تاريخاً صالحاً.',
    'delivery_date_after_or_equal' => 'يجب أن يكون تاريخ التوصيل اليوم أو تاريخاً في المستقبل.',
    'store_delivery_schedule_time_id_required_with' => 'وقت توصيل المتجر مطلوب عند اختيار المتجر.',
    'store_delivery_schedule_time_id_exists' => 'وقت توصيل المتجر المحدد غير صالح.',
    'clinic_delivery_schedule_time_id_required_with' => 'وقت توصيل العيادة مطلوب عند اختيار العيادة.',
    'clinic_delivery_schedule_time_id_exists' => 'وقت توصيل العيادة المحدد غير صالح.',
    'product_id_distinct' => 'لا يمكن إضافة المنتج أكثر من مرة.',
    'store_delivery_schedule_time_id_invalid'  => 'الوقت المحدد لا ينتمي إلى المتجر المحدد.',
    'clinic_delivery_schedule_time_id_invalid'  => 'الوقت المحدد لا ينتمي إلى العيادة المحددة.',
];
