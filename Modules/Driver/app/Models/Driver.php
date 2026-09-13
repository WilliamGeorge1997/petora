<?php

namespace Modules\Driver\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Clinic\Models\Clinic;
use Modules\Country\Models\City;
use Modules\Country\Models\Country;
use Modules\Country\Models\Zone;
use Modules\Store\Models\Store;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Driver extends Authenticatable
{
    use HasApiTokens, HasFactory, LogsActivity, Notifiable;

    protected $fillable = [
        'name',
        'password',
        'phone',
        'license_id',
        'image',
        'store_id',
        'clinic_id',
        'country_id',
        'city_id',
        'zone_id',
        'latitude',
        'longitude',
        'fcm_token',
        'locale',
        'allow_notification',
        'is_available',
        'is_active',
    ];

    protected $casts = [
        'password' => 'hashed',
        'allow_notification' => 'boolean',
        'is_available' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'password',
    ];

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Driver')
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->dontLogEmptyChanges();
    }

    // Date serialization
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d h:i A');
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['name'] ?? null, function (Builder $query, $name) {
                $query->where('name', 'LIKE', "%{$name}%");
            })
            ->when($filters['phone'] ?? null, function (Builder $query, $phone) {
                $query->where('phone', 'LIKE', "%{$phone}%");
            })
            ->when($filters['license_id'] ?? null, function (Builder $query, $licenseId) {
                $query->where('license_id', 'LIKE', "%{$licenseId}%");
            })
            ->when($filters['store_id'] ?? null, function (Builder $query, $storeId) {
                $query->where('store_id', $storeId);
            })
            ->when($filters['clinic_id'] ?? null, function (Builder $query, $clinicId) {
                $query->where('clinic_id', $clinicId);
            })
            ->when($filters['country_id'] ?? null, function (Builder $query, $countryId) {
                $query->where('country_id', $countryId);
            })
            ->when($filters['city_id'] ?? null, function (Builder $query, $cityId) {
                $query->where('city_id', $cityId);
            })
            ->when($filters['zone_id'] ?? null, function (Builder $query, $zoneId) {
                $query->where('zone_id', $zoneId);
            })
            ->when(isset($filters['is_available']) && $filters['is_available'] !== '', function (Builder $query) use ($filters) {
                $query->where('is_available', (bool) $filters['is_available']);
            })
            ->when(isset($filters['is_active']) && $filters['is_active'] !== '', function (Builder $query) use ($filters) {
                $query->where('is_active', (bool) $filters['is_active']);
            })
            ->when(($filters['latitude'] ?? null) && ($filters['longitude'] ?? null), function (Builder $query) use ($filters) {
                nearest($query, $filters['latitude'], $filters['longitude'], $filters['distance'] ?? null);
            });
    }

    // Getters
    public function getImageAttribute(?string $value): ?string
    {
        if ($value !== null && $value !== '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            return asset('storage/uploads/driver/'.$value);
        }

        return $value;
    }

    // Relations
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }
}
