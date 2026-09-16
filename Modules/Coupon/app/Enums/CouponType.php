<?php

namespace Modules\Coupon\Enums;

enum CouponType: string
{
    case Fixed = 'fixed';
    case Percent = 'percent';

    /**
     * Get the human-readable label for the type.
     */
    public function label(): string
    {
        return match ($this) {
            self::Fixed => __('coupon::type.fixed'),
            self::Percent => __('coupon::type.percent'),
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
        return array_reduce(self::cases(), function (array $carry, CouponType $type) {
            $carry[$type->value] = $type->label();

            return $carry;
        }, []);
    }
}
