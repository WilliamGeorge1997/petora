<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class StoreDeliverySchedule extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'store_id',
        'day',
    ];

    protected $hidden = ['store_id'];

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('StoreDeliverySchedule')
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
            ->when($filters['store_id'] ?? null, function ($query, $storeId) {
                $query->where('store_id', $storeId);
            })
            ->when($filters['day'] ?? null, function ($query, $day) {
                $query->where('day', $day);
            });
    }

    // Relations
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function times(): HasMany
    {
        return $this->hasMany(StoreDeliveryScheduleTime::class, 'store_delivery_schedule_id');
    }
}
