<?php

return [
    'booking' => [
        'fetched' => 'تم جلب الحجوزات بنجاح.',
        'created' => 'تم إنشاء الحجز بنجاح.',
        'updated' => 'تم تحديث الحجز بنجاح.',
        'deleted' => 'تم حذف الحجز بنجاح.',
        'unauthorized' => 'غير مصرح لك بتنفيذ هذا الإجراء.',
    ],
    'status' => [
        'fetched' => 'تم جلب حالات الحجز بنجاح.',
        'created' => 'تم إنشاء الحالة بنجاح.',
        'updated' => 'تم تحديث الحالة بنجاح.',
        'deleted' => 'تم حذف الحالة بنجاح.',
        'activated' => 'تم تفعيل الحالة بنجاح.',
        'deactivated' => 'تم تعطيل الحالة بنجاح.',
    ],

    'title_en_required' => 'العنوان بالإنجليزية مطلوب.',
    'title_en_string' => 'صيغة العنوان بالإنجليزية غير صالحة.',
    'title_en_max' => 'العنوان بالإنجليزية يجب ألا يتجاوز 255 حرفاً.',

    'title_ar_required' => 'العنوان بالعربية مطلوب.',
    'title_ar_string' => 'صيغة العنوان بالعربية غير صالحة.',
    'title_ar_max' => 'العنوان بالعربية يجب ألا يتجاوز 255 حرفاً.',

    'is_active_boolean' => 'قيمة الحالة غير صالحة.',

    'client_id_required' => 'العميل مطلوب.',
    'client_id_exists' => 'العميل المحدد غير صالح.',

    'clinic_service_id_required' => 'خدمة العيادة مطلوبة.',
    'clinic_service_id_exists' => 'خدمة العيادة المحددة غير صالحة.',

    'clinic_service_schedule_time_id_required' => 'الموعد الزمني مطلوب.',
    'clinic_service_schedule_time_id_exists' => 'الموعد الزمني المحدد غير صالح.',

    'pet_id_required' => 'الحيوان الأليف مطلوب.',
    'pet_id_exists' => 'الحيوان الأليف المحدد غير صالح.',

    'booking_date_required' => 'تاريخ الحجز مطلوب.',
    'booking_date_date' => 'تاريخ الحجز يجب أن يكون تاريخاً صالحاً.',
    'booking_date_after_or_equal' => 'تاريخ الحجز يجب أن يكون اليوم أو تاريخاً مستقبلياً.',

    'payment_method_id_required' => 'طريقة الدفع مطلوبة.',
    'payment_method_id_exists' => 'طريقة الدفع المحددة غير صالحة.',

    'slot_capacity_exceeded' => 'الموعد الزمني المحدد ممتلئ لهذا اليوم.',
];
