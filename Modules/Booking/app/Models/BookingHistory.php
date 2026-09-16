<?php

namespace Modules\Booking\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BookingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'booking_status_id',
        'notes',
        'historible_id',
        'historible_type',
    ];

    // Date serialization
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d h:i A');
    }

    // Relations
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(BookingStatus::class, 'booking_status_id');
    }

    public function historible(): MorphTo
    {
        return $this->morphTo();
    }
}
