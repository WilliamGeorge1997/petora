<?php

namespace Modules\Community\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Client\Models\Client;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'follower_id',
        'following_id',
    ];

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
