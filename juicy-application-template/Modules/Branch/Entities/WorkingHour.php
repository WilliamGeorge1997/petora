<?php

namespace Modules\Branch\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Branch\Entities\Branch;

class WorkingHour extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'day', 'is_open_24_hours', 'from', 'to'];


    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
}
