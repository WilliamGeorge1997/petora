<?php

namespace Modules\Community\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Client\Models\Client;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Comment extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'client_id',
        'post_id',
        'parent_id',
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'parent_id',
        'post_id',
        'client_id',
    ];

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Comment')
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

    public function scopeWithIsLiked(Builder $query): Builder
    {
        return $query->when(auth('client')->check(), function ($q) {
            $q->withExists(['likes as is_liked' => function ($query) {
                $query->where('client_id', auth('client')->id());
            }]);
        });
    }

    // Scopes
    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['content'] ?? null, function ($query, $content) {
            $query->where('content', 'LIKE', "%{$content}%");
        })
            ->when($filters['client_id'] ?? null, function ($query, $clientId) {
                $query->where('client_id', $clientId);
            })
            ->when($filters['post_id'] ?? null, function ($query, $postId) {
                $query->where('post_id', $postId);
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

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
