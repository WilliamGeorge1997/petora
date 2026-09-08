<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ClinicServiceSchedule extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'clinic_service_id',
        'day',
    ];

    protected $hidden = ['clinic_service_id'];

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('ClinicServiceSchedule')
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

    // Scopes
    public function scopeDay(Builder $query, string $day): Builder
    {
        return $query->where('day', $day);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['clinic_service_id'] ?? null, function ($query, $clinicServiceId) {
                $query->where('clinic_service_id', $clinicServiceId);
            })
            ->when($filters['day'] ?? null, function ($query, $day) {
                $query->where('day', $day);
            });
    }

    // Relations
    public function clinicService(): BelongsTo
    {
        return $this->belongsTo(ClinicService::class);
    }

    public function times(): HasMany
    {
        return $this->hasMany(ClinicServiceScheduleTime::class);
    }
}
