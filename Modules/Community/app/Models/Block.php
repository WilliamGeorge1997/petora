<?php

namespace Modules\Community\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Client\Models\Client;

class Block extends Model
{
    use HasFactory;

    protected $fillable = [
        'blocker_id',
        'blocked_id',
    ];

    //Relations
    public function blocker(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'blocker_id');
    }

    public function blocked(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'blocked_id');
    }
}
