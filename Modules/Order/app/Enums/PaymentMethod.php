<?php

namespace Modules\Order\Enums;

enum PaymentMethod: int
{
    case CashOnDelivery = 1;
    case PaymentOnline = 2;

    /**
     * Get the human-readable label for the payment method.
     */
    public function label(): string
    {
        return match ($this) {
            self::CashOnDelivery => __('order::payment_method.cash_on_delivery'),
            self::PaymentOnline => __('order::payment_method.payment_online'),
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
