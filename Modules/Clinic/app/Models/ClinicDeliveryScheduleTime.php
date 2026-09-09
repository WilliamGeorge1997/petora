<?php

namespace Modules\Clinic\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ClinicDeliveryScheduleTime extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'clinic_delivery_schedule_id',
        'from',
        'to',
    ];

    protected $hidden = ['clinic_delivery_schedule_id'];

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('ClinicDeliveryScheduleTime')
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->dontLogEmptyChanges();
    }

    // Date serialization
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d h:i A');
    }

    // Relations
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(ClinicDeliverySchedule::class, 'clinic_delivery_schedule_id');
    }
}
