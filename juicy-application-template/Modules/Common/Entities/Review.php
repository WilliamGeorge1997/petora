<?php

namespace Modules\Common\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Entities\Branch;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'phone',
        'food_quality',
        'service_speed',
        'staff',
        'cleanliness',
        'will_revisit',
        'comment',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    protected function scopeAvailable($query)
    {
        if (!Auth::check()) {
            return;
        }

        $admin = Auth::user();

        if ($admin->hasRole('Super Admin')) {
            return;
        }

        if ($admin->hasRole('Branch Manager')) {
            $query->where('branch_id', $admin->branch_id);
        }
    }
}
