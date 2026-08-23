<?php

namespace Modules\Branch\Entities;

use Modules\Branch\Entities\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShortUrl extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'url', 'branch_id'];
    protected $hidden = ['created_at', 'updated_at'];

    //Relations
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
