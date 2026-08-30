<?php

namespace Modules\Client\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Translatable\HasTranslations;

class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, HasTranslations, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'image',
        'locale',
        'fcm_token',
        'verify_code',
        'is_active',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
        'allow_notification' => 'boolean'
    ];

    protected $hidden = [
        'password',
        'verify_code'
    ];

    //Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Client')
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
}
