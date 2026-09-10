<?php

namespace Modules\Common\Enums;

enum WeekDay: string
{
    case Saturday = 'saturday';
    case Sunday = 'sunday';
    case Monday = 'monday';
    case Tuesday = 'tuesday';
    case Wednesday = 'wednesday';
    case Thursday = 'thursday';
    case Friday = 'friday';

    /**
     * Get the human-readable label for the day.
     */
    public function label(): string
    {
        return match ($this) {
            self::Saturday => __('common::general.days.saturday'),
            self::Sunday => __('common::general.days.sunday'),
            self::Monday => __('common::general.days.monday'),
            self::Tuesday => __('common::general.days.tuesday'),
            self::Wednesday => __('common::general.days.wednesday'),
            self::Thursday => __('common::general.days.thursday'),
            self::Friday => __('common::general.days.friday'),
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
        return array_reduce(self::cases(), function (array $carry, WeekDay $day) {
            $carry[$day->value] = $day->label();
            return $carry;
        }, []);
    }
}
