<?php

namespace Modules\Pet\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Client\Models\Client;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Pet extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'client_id',
        'pet_type_id',
        'name',
        'breed',
        'gender',
        'date_of_birth',
        'weight',
        'image',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'weight' => 'decimal:2',
    ];

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Pet')
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
    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['name'] ?? null, function ($query, $name) {
            $query->where('name', 'LIKE', "%{$name}%");
        })
            ->when($filters['client_id'] ?? null, function ($query, $clientId) {
                $query->where('client_id', $clientId);
            })
            ->when($filters['pet_type_id'] ?? null, function ($query, $petTypeId) {
                $query->where('pet_type_id', $petTypeId);
            })
            ->when($filters['gender'] ?? null, function ($query, $gender) {
                $query->where('gender', $gender);
            });
    }

    // Getters
    public function getImageAttribute(?string $value): ?string
    {
        if ($value !== null && $value !== '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            return asset('storage/uploads/pet/'.$value);
        }

        return $value;
    }

    // Relations
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(PetType::class, 'pet_type_id');
    }
}
