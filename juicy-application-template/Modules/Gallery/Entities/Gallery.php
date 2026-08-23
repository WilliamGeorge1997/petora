<?php

namespace Modules\Gallery\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Branch\Entities\Branch;
use Spatie\Activitylog\Traits\LogsActivity;

class Gallery extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['branch_id', 'image', 'sort_order', 'is_active'];

    protected static $logName = 'Gallery';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;


    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            return asset('uploads/gallery/' . $value);
        }
        return $value;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeAvailable($query)
    {
        if (auth()->guard('admin')->check()) {
            /** @var \Modules\Admin\Entities\Admin $admin */
            $admin = auth()->guard('admin')->user();
            if ($admin->hasRole('Branch Manager')) {
                return $query->where('branch_id', $admin->branch_id);
            }
            return $query;
        }
        return $query;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
