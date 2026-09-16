<?php

return [
    'booking' => [
        'fetched' => 'Bookings fetched successfully.',
        'created' => 'Booking created successfully.',
        'updated' => 'Booking updated successfully.',
        'deleted' => 'Booking deleted successfully.',
        'unauthorized' => 'You are not authorized to perform this action.',
    ],
    'status' => [
        'fetched' => 'Booking statuses fetched successfully.',
        'created' => 'Status created successfully.',
        'updated' => 'Status updated successfully.',
        'deleted' => 'Status deleted successfully.',
        'activated' => 'Status activated successfully.',
        'deactivated' => 'Status deactivated successfully.',
    ],

    'title_en_required' => 'Title in English is required.',
    'title_en_string' => 'Invalid Title in English format.',
    'title_en_max' => 'Title in English must not exceed 255 characters.',

    'title_ar_required' => 'Title in Arabic is required.',
    'title_ar_string' => 'Invalid Title in Arabic format.',
    'title_ar_max' => 'Title in Arabic must not exceed 255 characters.',

    'is_active_boolean' => 'Invalid status value.',

    'client_id_required' => 'Client is required.',
    'client_id_exists' => 'Selected client is invalid.',

    'clinic_service_id_required' => 'Clinic service is required.',
    'clinic_service_id_exists' => 'Selected clinic service is invalid.',

    'clinic_service_schedule_time_id_required' => 'Time slot is required.',
    'clinic_service_schedule_time_id_exists' => 'Selected time slot is invalid.',

    'pet_id_required' => 'Pet is required.',
    'pet_id_exists' => 'Selected pet is invalid.',

    'booking_date_required' => 'Booking date is required.',
    'booking_date_date' => 'Booking date must be a valid date.',
    'booking_date_after_or_equal' => 'Booking date must be today or a future date.',

    'payment_method_id_required' => 'Payment method is required.',
    'payment_method_id_exists' => 'Selected payment method is invalid.',

    'slot_capacity_exceeded' => 'The selected time slot is full for this date.',
];
