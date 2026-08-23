<?php

namespace Modules\Branch\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CallWaiter extends Model
{
    use HasFactory;

    protected $fillable = ['table', 'branch_id', 'status'];
    
}
