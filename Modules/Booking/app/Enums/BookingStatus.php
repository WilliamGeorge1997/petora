<?php

namespace Modules\Booking\Enums;

enum BookingStatus: int
{
    case Pending = 1;
    case Confirmed = 2;
    case InProgress = 3;
    case Completed = 4;
    case Cancelled = 5;

    /**
     * Get the human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => __('booking::status.pending'),
            self::Confirmed => __('booking::status.confirmed'),
            self::InProgress => __('booking::status.in_progress'),
            self::Completed => __('booking::status.completed'),
            self::Cancelled => __('booking::status.cancelled'),
        };
    }

    /**
     * Get all enum values as an array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get an associative array of value => label for dropdowns.
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
