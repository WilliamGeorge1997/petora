<?php

namespace Modules\Booking\ViewModels;

use Modules\Booking\Services\BookingStatusService;

class BookingViewModel
{
    public function bookingStatuses()
    {
        return (new BookingStatusService)->active(columns: ['id', 'title']);
    }
}
