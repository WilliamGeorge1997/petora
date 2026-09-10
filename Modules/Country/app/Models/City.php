<?php

namespace Modules\Country\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Translatable\HasTranslations;

class City extends Model
{
    use HasFactory, HasTranslations, LogsActivity;

    protected $fillable = [
        'country_id',
        'title',
        'is_active',
    ];

    public array $translatable = ['title'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('City')
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->dontLogEmptyChanges();
    }

    // Date serialization
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Relations
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function zones(): HasMany
    {
        return $this->hasMany(Zone::class);
    }
}
