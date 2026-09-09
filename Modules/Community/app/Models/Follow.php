<?php

namespace Modules\Community\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Client\Models\Client;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Follow extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'follower_id',
        'following_id',
    ];

    //Date serialization
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    //Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Follow')
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->dontLogEmptyChanges();
    }

    //Scopes
    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when(isset($filters['follower_id']), function ($query) use ($filters) {
            $query->where('follower_id', $filters['follower_id']);
        })
        ->when(isset($filters['following_id']), function ($query) use ($filters) {
            $query->where('following_id', $filters['following_id']);
        });
    }

    //Relations
    public function follower(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'follower_id');
    }

    public function following(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'following_id');
    }
}
