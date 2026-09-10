<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Clinic\Models\Clinic;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ClinicService extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'clinic_id',
        'service_id',
        'price',
        'duration',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer',
        'is_active' => 'boolean',
    ];

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('ClinicService')
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
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['clinic_id'] ?? null, function ($query, $clinicId) {
                $query->where('clinic_id', $clinicId);
            })
            ->when($filters['service_id'] ?? null, function ($query, $serviceId) {
                $query->where('service_id', $serviceId);
            })
            ->when(isset($filters['is_active']) && $filters['is_active'] !== '', function ($query) use ($filters) {
                $query->where('is_active', (bool) $filters['is_active']);
            });
    }

    // Relations
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ClinicServiceSchedule::class);
    }
}
