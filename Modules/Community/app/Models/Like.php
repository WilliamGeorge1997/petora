<?php

namespace Modules\Community\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Client\Models\Client;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'likeable_id',
        'likeable_type',
    ];

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
