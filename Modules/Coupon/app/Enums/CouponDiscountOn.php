<?php

namespace Modules\Coupon\Enums;

enum CouponDiscountOn: string
{
    case Subtotal = 'subtotal';
    case Delivery = 'delivery';
    case Both = 'both';

    /**
     * Get the human-readable label for the type.
     */
    public function label(): string
    {
        return match ($this) {
            self::Subtotal => __('coupon::discount_on.subtotal'),
            self::Delivery => __('coupon::discount_on.delivery'),
            self::Both => __('coupon::discount_on.both'),
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
        return array_reduce(self::cases(), function (array $carry, CouponDiscountOn $type) {
            $carry[$type->value] = $type->label();

            return $carry;
        }, []);
    }
}
