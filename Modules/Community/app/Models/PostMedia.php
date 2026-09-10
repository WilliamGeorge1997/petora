<?php

namespace Modules\Community\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class PostMedia extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'post_media';

    protected $fillable = [
        'post_id',
        'media',
        'is_video',
    ];

    protected $casts = [
        'is_video' => 'boolean',
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
            ->useLogName('PostMedia')
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->dontLogEmptyChanges();
    }

    // Getters
    public function getMediaAttribute(?string $value): ?string
    {
        if ($value !== null && $value !== '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            return asset('storage/uploads/post/' . $value);
        }

        return $value;
    }

    // Relations
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
