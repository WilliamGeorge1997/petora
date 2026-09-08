<?php

namespace Modules\Clinic\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ClinicWorkingHour extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'clinic_id',
        'day',
        'from',
        'to',
        'is_open_24_hours',
    ];

    protected $casts = [
        'is_open_24_hours' => 'boolean',
    ];

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('ClinicWorkingHour')
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
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
