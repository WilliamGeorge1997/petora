<?php

namespace Modules\Subscription\Entities;

use Modules\Branch\Entities\Branch;
use Modules\Package\Entities\Package;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'package_id', 'price', 'is_active', 'start_date', 'end_date', 'duration_months', 'product_count'];

    protected static $logName = 'Subscription';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }


    //Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    public function scopeInactive($query)
    {
        return $query->where('is_active', 0);
    }


    //Relations
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
