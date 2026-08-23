<?php

namespace Modules\Branch\Entities;

use Illuminate\Database\Eloquent\Model;

class BranchOrderDiscount extends Model
{
    protected $fillable = [
        'branch_id',
        'min_total',
        'type',
        'value'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function discount($total)
    {
        if ($this->type == 'percent') {
            return $total * ($this->value / 100);
        }
        return $this->value;
    }
}
