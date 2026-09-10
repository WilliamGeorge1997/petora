<?php

namespace Modules\Community\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Client\Models\Client;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Story extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'client_id',
        'media',
        'is_video',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'is_video' => 'boolean',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Story')
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

    // Getters
    public function getMediaAttribute(?string $value): ?string
    {
        if ($value !== null && $value !== '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            return asset('storage/uploads/story/' . $value);
        }

        return $value;
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeWithIsLiked(Builder $query): Builder
    {
        return $query->when(auth('client')->check(), function ($q) {
            $q->withExists(['likes as is_liked' => function ($query) {
                $query->where('client_id', auth('client')->id());
            }]);
        });
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        if (! empty($filters['following_only']) && auth('client')->check()) {
            /** @var Client $client */
            $client = auth('client')->user();

            $query->whereIn('client_id', Follow::where('follower_id', $client->id)->select('following_id'));
        }

        $query->when($filters['client_id'] ?? null, function ($query, $clientId) {
            $query->where('client_id', $clientId);
        })
            ->when(isset($filters['is_active']) && $filters['is_active'] !== '', function ($query) use ($filters) {
                $query->where('is_active', (bool) $filters['is_active']);
            });
    }

    // Relations
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
