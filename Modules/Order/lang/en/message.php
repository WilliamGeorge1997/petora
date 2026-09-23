<?php

return [
    'order' => [
        'fetched' => 'Orders fetched successfully.',
        'created' => 'Order created successfully.',
        'updated' => 'Order updated successfully.',
        'deleted' => 'Order deleted successfully.',
        'cancelled' => 'Order cancelled successfully.',
        'cannot_cancel' => 'Order cannot be cancelled at this stage.',
    ],
    'order_method' => [
        'fetched' => 'Order methods fetched successfully.',
    ],
    'payment_method' => [
        'fetched' => 'Payment methods fetched successfully.',
        'created' => 'Payment method created successfully.',
        'updated' => 'Payment method updated successfully.',
        'deleted' => 'Payment method deleted successfully.',
        'activated' => 'Payment method activated successfully.',
        'deactivated' => 'Payment method deactivated successfully.',
    ],
    'order_status' => [
        'fetched' => 'Order statuses fetched successfully.',
    ],

    'title_en_required' => 'Title in English is required.',
    'title_en_string' => 'Invalid Title in English format.',
    'title_en_max' => 'Title in English must not exceed 255 characters.',

    'title_ar_required' => 'Title in Arabic is required.',
    'title_ar_string' => 'Invalid Title in Arabic format.',
    'title_ar_max' => 'Title in Arabic must not exceed 255 characters.',

    'is_active_boolean' => 'Invalid status value.',

    'order_method_id_required' => 'Order method is required.',
    'payment_method_id_required' => 'Payment method is required.',
    'address_id_required' => 'Address is required.',
    'address_id_exists' => 'Selected address is invalid.',
    'items_required' => 'Items are required.',
    'items_array' => 'Items must be an array.',
    'product_id_required' => 'Product is required.',
    'product_id_exists' => 'Selected product is invalid.',
    'quantity_required' => 'Quantity is required.',
    'quantity_min' => 'Quantity must be at least 1.',
    'store_id_required_without' => 'Store is required when clinic is not present.',
    'store_id_exists' => 'Selected store is invalid.',
    'store_id_prohibits' => 'Store and clinic cannot be selected together.',
    'clinic_id_required_without' => 'Clinic is required when store is not present.',
    'clinic_id_exists' => 'Selected clinic is invalid.',
    'clinic_id_prohibits' => 'Clinic and store cannot be selected together.',
    'delivery_date_required' => 'Delivery date is required.',
    'delivery_date_date' => 'Delivery date must be a valid date.',
    'delivery_date_after_or_equal' => 'Delivery date must be today or a future date.',
    'store_delivery_schedule_time_id_required_with' => 'Store delivery time is required when store is selected.',
    'store_delivery_schedule_time_id_exists' => 'Selected store delivery time is invalid.',
    'clinic_delivery_schedule_time_id_required_with' => 'Clinic delivery time is required when clinic is selected.',
    'clinic_delivery_schedule_time_id_exists' => 'Selected clinic delivery time is invalid.',
    'product_id_distinct' => 'Each product can only be added once.',
    'store_delivery_schedule_time_id_invalid'  => 'The selected delivery time does not belong to the chosen store.',
    'clinic_delivery_schedule_time_id_invalid'  => 'The selected delivery time does not belong to the chosen clinic.',
];
