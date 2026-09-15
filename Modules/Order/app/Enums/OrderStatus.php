<?php

namespace Modules\Order\Enums;

enum OrderStatus: int
{
    case Sent = 1;
    case AcceptedAndPreparing = 2;
    case DeliverToDriver = 3;
    case OnTheWay = 4;
    case Done = 5;
    case RefusedByDriver = 6;
    case Fail = 7;
    case Cancelled = 8;

    /**
     * Get the human-readable label for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Sent => __('order::status.sent'),
            self::AcceptedAndPreparing => __('order::status.accepted_and_preparing'),
            self::DeliverToDriver => __('order::status.deliver_to_driver'),
            self::OnTheWay => __('order::status.on_the_way'),
            self::Done => __('order::status.done'),
            self::RefusedByDriver => __('order::status.refused_by_driver'),
            self::Fail => __('order::status.fail'),
            self::Cancelled => __('order::status.cancelled'),
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
