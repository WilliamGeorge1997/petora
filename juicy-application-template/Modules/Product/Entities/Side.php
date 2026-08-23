<?php

namespace Modules\Product\Entities;

use Modules\Branch\Entities\Branch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\SideValue;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Side extends Model
{
    use HasFactory, HasTranslations, LogsActivity;

    protected $fillable = ['title', 'branch_id', 'max_selection', 'is_active', 'sort_order', 'display'];
    public $translatable = ['title', 'display'];


    protected static $logName = 'Side';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;

    //Serialize Date
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    //Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function values()
    {
        return $this->hasMany(SideValue::class, 'side_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function scopeAvailable($query)
    {
        if (Auth::check()) {
            $admin = Auth::user();
            if ($admin->hasRole('Super Admin')) {
                // show all data
            } else if ($admin->hasRole('Branch Manager')) {
                $query->where('branch_id', $admin->branch_id);
            }
        }
    }
}
