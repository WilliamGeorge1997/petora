<?php

namespace Modules\Community\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Client\Models\Client;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Block extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'blocker_id',
        'blocked_id',
    ];

    // Date serialization
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Block')
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->dontLogEmptyChanges();
    }

    // Relations
    public function blocker(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'blocker_id');
    }

    public function blocked(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'blocked_id');
    }
}
