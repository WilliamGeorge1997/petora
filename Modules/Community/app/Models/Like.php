<?php

namespace Modules\Community\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Client\Models\Client;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'likeable_id',
        'likeable_type',
    ];

    //Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Like')
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


    //Relations
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }
}
