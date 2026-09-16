<?php

namespace Modules\Booking\Services;

use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingHistory;

class BookingHistoryService
{
    /**
     * Log a new history entry for a booking.
     */
    public function save(Booking $booking, int $statusId, ?int $historibleId = null, ?string $historibleType = null, ?string $notes = null): BookingHistory
    {
        return $booking->histories()->create([
            'booking_status_id' => $statusId,
            'notes' => $notes,
            'historible_id' => $historibleId,
            'historible_type' => $historibleType,
        ]);
    }
}
