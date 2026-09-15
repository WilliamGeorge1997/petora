<?php

namespace Modules\Order\Enums;

enum OrderMethod: int
{
    case HomeDelivery = 1;

    /**
     * Get the human-readable label for the order method.
     */
    public function label(): string
    {
        return match ($this) {
            self::HomeDelivery => __('order::order_method.home_delivery'),
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
