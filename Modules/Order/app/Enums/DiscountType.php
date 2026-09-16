<?php

namespace Modules\Order\Enums;

enum DiscountType: int
{
    case Coupon = 1;

    public function label(): string
    {
        return match ($this) {
            self::Coupon => __('order::discount_type.coupon'),
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
        return array_reduce(self::cases(), function (array $carry, DiscountType $type) {
            $carry[$type->value] = $type->label();

            return $carry;
        }, []);
    }
}
