<?php

namespace Modules\Service\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasFactory, HasTranslations, LogsActivity;

    protected $fillable = [
        'title',
        'description',
        'image',
        'price',
        'duration',
        'is_active',
    ];

    public array $translatable = ['title', 'description'];

    protected $casts = [
        'price' => 'decimal:2',
        'duration' => 'integer',
        'is_active' => 'boolean',
    ];

    //Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Service')
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
            ->when(isset($filters['is_active']) && $filters['is_active'] !== '', function ($query) use ($filters) {
                $query->where('is_active', (bool) $filters['is_active']);
            });
    }

    //Getters
    public function getImageAttribute(?string $value): ?string
    {
        if ($value !== null && $value !== '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            return asset('storage/uploads/service/' . $value);
        }

        return $value;
    }

    //Relations
    public function clinics(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\Modules\Clinic\Models\Clinic::class, 'clinic_services')
            ->withPivot(['price', 'duration', 'is_active'])
            ->withTimestamps();
    }

    public function clinicServices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Service\Models\ClinicService::class);
    }
}
