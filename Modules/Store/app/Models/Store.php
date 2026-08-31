<?php

namespace Modules\Store\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Company\Models\Company;
use Modules\Country\Models\City;
use Modules\Country\Models\Country;
use Modules\Country\Models\Zone;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Store extends Model
{
    use HasFactory, HasTranslations, LogsActivity;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'address',
        'phone',
        'image',
        'is_active',
        'country_id',
        'city_id',
        'zone_id',
        'latitude',
        'longitude',
    ];

    public array $translatable = ['title', 'description', 'address'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    //Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Store')
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->dontLogEmptyChanges();
    }

    //Date serialization
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    //Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['title'] ?? null, function ($query, $title) {
            $query->whereJsonContainsLocales('title', ['en', 'ar'], "%{$title}%", 'LIKE');
        })
            ->when($filters['country_id'] ?? null, function ($query, $countryId) {
                $query->where('country_id', $countryId);
            })
            ->when($filters['city_id'] ?? null, function ($query, $cityId) {
                $query->where('city_id', $cityId);
            })
            ->when($filters['zone_id'] ?? null, function ($query, $zoneId) {
                $query->where('zone_id', $zoneId);
            })
            ->when(isset($filters['is_active']) && $filters['is_active'] !== '', function ($query) use ($filters) {
                $query->where('is_active', (bool) $filters['is_active']);
            })
            ->when(($filters['latitude'] ?? null) && ($filters['longitude'] ?? null), function ($query) use ($filters) {
                nearest($query, $filters['latitude'], $filters['longitude'], $filters['distance'] ?? null);
            });
    }

    //Getters
    public function getImageAttribute(?string $value): ?string
    {
        if ($value !== null && $value !== '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            return asset('uploads/store/' . $value);
        }

        return $value;
    }

    //Relations
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
