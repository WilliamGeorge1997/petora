<?php

namespace Modules\Common\Helpers;

use Illuminate\Support\Carbon;

class SerialGenerator
{
    /**
     * Generate a unique serial number for any model.
     *
     * Format: {prefix}{reversed_year}{reversed_month}{reversed_day}{zero_padded_today_count}
     * Example: ORD-749691190001
     *
     * To change the serial format later, edit only this method.
     *
     * @param  class-string  $model   The Eloquent model class to count today's records from.
     * @param  string        $prefix  e.g. 'ORD-' for orders, 'APP-' for appointments.
     */
    public static function generate(string $model, string $prefix): string
    {
        $todayCount = $model::whereDate('created_at', Carbon::today())->count() + 1;

        return $prefix
            . (100 - date('y'))
            . (100 - date('m'))
            . (100 - date('d'))
            . str_pad($todayCount, 4, '0', STR_PAD_LEFT);
    }
}
