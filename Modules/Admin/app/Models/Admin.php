<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasApiTokens, HasRoles, LogsActivity, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'phone', 'image', 'locale', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Admin')
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
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', 1);
    }

    // Getters
    public function getImageAttribute(?string $value): ?string
    {
        if ($value !== null && $value !== '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            return asset('storage/uploads/admin/'.$value);
        }

        return $value;
    }
}
