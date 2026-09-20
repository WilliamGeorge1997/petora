<?php

namespace Modules\Company\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Admin\Enums\AdminRole;
use Modules\Admin\Models\Admin;
use Modules\Store\Models\Store;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Translatable\HasTranslations;

class Company extends Model
{
    use HasFactory, HasTranslations, LogsActivity;

    protected $fillable = [
        'title',
        'description',
        'phone',
        'address',
        'image',
        'is_active',
    ];

    public array $translatable = ['title', 'description', 'address'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Company')
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

    // Getters
    public function getImageAttribute(?string $value): ?string
    {
        if ($value !== null && $value !== '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            return asset('storage/uploads/company/' . $value);
        }

        return $value;
    }

    public function scopeAvailable(Builder $query): Builder
    {
        /** @var Admin|null $admin */
        $admin = auth('admin')->user();

        if ($admin) {
            if ($admin->hasRole(AdminRole::SuperAdmin->value)) {
                return $query;
            }

            if ($admin->hasRole([AdminRole::CompanyManager->value, AdminRole::StoreManager->value])) {
                return $query->where('id', $admin->company_id);
            }

            // Fallback for any other admin role without company access
            return $query->whereRaw('1 = 0');
        }

        return $query;
    }



    // Relations
    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }
}
