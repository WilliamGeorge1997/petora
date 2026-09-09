<?php

return [
    'hashtag' => [
        'index' => 'الهاشتاجات',
        'fetched' => 'تم جلب الهاشتاجات بنجاح',
        'created' => 'تم إنشاء الهاشتاج بنجاح',
        'updated' => 'تم تحديث الهاشتاج بنجاح',
        'deleted' => 'تم حذف الهاشتاج بنجاح',
        'activated' => 'تم تفعيل الهاشتاج بنجاح',
        'deactivated' => 'تم إلغاء تفعيل الهاشتاج بنجاح',
    ],
    'post' => [
        'index' => 'المنشورات',
        'fetched' => 'تم جلب المنشورات بنجاح',
        'created' => 'تم إنشاء المنشور بنجاح',
        'updated' => 'تم تحديث المنشور بنجاح',
        'deleted' => 'تم حذف المنشور بنجاح',
        'activated' => 'تم تفعيل المنشور بنجاح',
        'deactivated' => 'تم إلغاء تفعيل المنشور بنجاح',
    ],
    'comment' => [
        'index' => 'التعليقات',
        'fetched' => 'تم جلب التعليقات بنجاح',
        'created' => 'تم إنشاء التعليق بنجاح',
        'updated' => 'تم تحديث التعليق بنجاح',
        'deleted' => 'تم حذف التعليق بنجاح',
        'activated' => 'تم تفعيل التعليق بنجاح',
        'deactivated' => 'تم إلغاء تفعيل التعليق بنجاح',
    ],
    'like' => [
        'index' => 'الإعجابات',
        'fetched' => 'تم جلب الإعجابات بنجاح',
        'created' => 'تم إنشاء الإعجاب بنجاح',
        'deleted' => 'تم حذف الإعجاب بنجاح',
    ],
    'story' => [
        'index' => 'القصص',
        'fetched' => 'تم جلب القصص بنجاح',
        'created' => 'تم إنشاء القصة بنجاح',
        'updated' => 'تم تحديث القصة بنجاح',
        'deleted' => 'تم حذف القصة بنجاح',
        'activated' => 'تم تفعيل القصة بنجاح',
        'deactivated' => 'تم إلغاء تفعيل القصة بنجاح',
    ],
    'follow' => [
        'followers_fetched' => 'تم جلب المتابعين بنجاح.',
        'following_fetched' => 'تم جلب قائمة المتابعة بنجاح.',
        'created' => 'تم اضافة المتابعة بنجاح',
        'deleted' => 'تم حذف المتابعة بنجاح',
    ],
    'follow_self' => 'لا يمكنك متابعة نفسك.',
    'block' => [
        'index' => 'الحظر',
        'fetched' => 'تم جلب الحظر بنجاح',
        'created' => 'تم إنشاء الحظر بنجاح',
        'deleted' => 'تم حذف الحظر بنجاح',
    ],

    // رسائل التحقق (Validation Messages)
    'client_id_required' => 'حقل العميل مطلوب.',
    'client_id_integer' => 'اختيار العميل غير صالح.',
    'client_id_exists' => 'العميل المحدد غير موجود.',

    'pet_id_required' => 'حقل الحيوان الأليف مطلوب.',
    'pet_id_integer' => 'اختيار الحيوان الأليف غير صالح.',
    'pet_id_exists' => 'الحيوان الأليف المحدد غير موجود.',

    'post_id_required' => 'حقل المنشور مطلوب.',
    'post_id_integer' => 'اختيار المنشور غير صالح.',
    'post_id_exists' => 'المنشور المحدد غير موجود.',

    'parent_id_required' => 'حقل التعليق الأصلي مطلوب.',
    'parent_id_integer' => 'اختيار التعليق الأصلي غير صالح.',
    'parent_id_exists'     => 'التعليق الأصلي المحدد غير موجود.',
    'parent_id_max_depth'  => 'يمكنك الرد على تعليق فقط، وليس على رد.',

    'content_required' => 'حقل المحتوى مطلوب.',
    'content_or_media_required' => 'يجب إدخال نص المنشور أو وسائط على الأقل.',
    'content_string' => 'يجب أن يكون المحتوى نصاً.',

    'is_active_required' => 'حقل الحالة مطلوب.',
    'is_active_boolean' => 'تنسيق الحالة غير صالح.',

    'media_required' => 'حقل الوسائط مطلوب.',
    'media_invalid' => 'الملف المرفوع غير صالح.',
    'media_mimes' => 'يجب أن تكون الوسائط ملفاً من نوع: jpeg, png, jpg, webp, svg, gif, mp4, mov, avi.',
    'media_max' => 'يجب ألا يتجاوز حجم الوسائط 20480 كيلوبايت.',

    'is_video_required' => 'تحديد نوع الفيديو مطلوب.',
    'is_video_boolean' => 'تنسيق تحديد الفيديو غير صالح.',

    'likeable_id_required' => 'حقل العنصر المعجب به مطلوب.',
    'likeable_id_integer' => 'اختيار العنصر المعجب به غير صالح.',
    'likeable_id_exists' => 'العنصر المعجب به المحدد غير موجود.',
    'likeable_type_required' => 'حقل نوع العنصر المعجب به مطلوب.',
    'likeable_type_string' => 'تنسيق نوع العنصر غير صالح.',

    'follower_id_required' => 'حقل المتابع مطلوب.',
    'follower_id_integer' => 'اختيار المتابع غير صالح.',
    'follower_id_exists' => 'المتابع المحدد غير موجود.',

    'following_id_required' => 'حقل المتابَع مطلوب.',
    'following_id_integer' => 'اختيار المتابَع غير صالح.',
    'following_id_exists' => 'المتابَع المحدد غير موجود.',

    'blocker_id_required' => 'حقل الحاظر مطلوب.',
    'blocker_id_integer' => 'اختيار الحاظر غير صالح.',
    'blocker_id_exists' => 'الحاظر المحدد غير موجود.',

    'blocked_id_required' => 'حقل المحظور مطلوب.',
    'blocked_id_integer' => 'اختيار المحظور غير صالح.',
    'blocked_id_exists' => 'المحظور المحدد غير موجود.',

    'expires_at_required' => 'تاريخ الانتهاء مطلوب.',
    'expires_at_date' => 'يجب أن يكون تاريخ الانتهاء صالحاً.',

    'text_required' => 'نص الهاشتاج مطلوب.',
    'text_string' => 'يجب أن يكون نص الهاشتاج نصاً.',
    'text_unique' => 'هذا الهاشتاج موجود بالفعل.',

    'hashtags_array' => 'يجب أن تكون الوسوم مصفوفة.',
    'hashtags_string' => 'يجب أن يكون كل وسم نصاً.',
    'hashtags_max' => 'يجب ألا يتجاوز طول كل وسم 100 حرف.',
];
