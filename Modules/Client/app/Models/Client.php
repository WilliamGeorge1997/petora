<?php

namespace Modules\Client\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Community\Models\Follow;
use Modules\Community\Models\Story;
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
        'allow_notification' => 'boolean',
    ];

    protected $hidden = [
        'password',
        'verify_code',
    ];

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Client')
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

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['name'] ?? null, function (Builder $query, $name) {
                $query->where('name', 'LIKE', "%{$name}%");
            })
            ->when($filters['email'] ?? null, function (Builder $query, $email) {
                $query->where('email', 'LIKE', "%{$email}%");
            })
            ->when($filters['phone'] ?? null, function (Builder $query, $phone) {
                $query->where('phone', 'LIKE', "%{$phone}%");
            })
            ->when(isset($filters['is_active']) && $filters['is_active'] !== '', function (Builder $query) use ($filters) {
                $query->where('is_active', (bool) $filters['is_active']);
            });
    }

    // Getters
    public function getImageAttribute(?string $value): ?string
    {
        if ($value !== null && $value !== '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            return asset('storage/uploads/client/'.$value);
        }

        return $value;
    }

    // Relations
    public function followers()
    {
        return $this->hasMany(Follow::class, 'following_id');
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function stories()
    {
        return $this->hasMany(Story::class);
    }
}
