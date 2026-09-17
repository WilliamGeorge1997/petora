<?php

namespace Modules\Notification\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Translatable\HasTranslations;

class Notification extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'image',
        'notifiable_id',
        'notifiable_type',
        'subject_id',
        'subject_type',
        'group_by',
        'read_at',
    ];

    public array $translatable = ['title', 'description'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d h:i A');
    }

    public function getImageAttribute(?string $value): ?string
    {
        if ($value !== null && $value !== '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            return asset('storage/uploads/notification/' . $value);
        }

        return $value;
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function order(): MorphTo
    {
        return $this->subject();
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['title'] ?? null, function (Builder $query, $title) {
                $query->where('title', 'LIKE', "%{$title}%");
            })
            ->when($filters['group_by'] ?? null, function (Builder $query, $groupBy) {
                $query->where('group_by', $groupBy);
            });
    }
}
