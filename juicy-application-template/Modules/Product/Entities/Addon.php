<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Entities\Branch;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Addon extends Model
{
    use HasFactory, HasTranslations, LogsActivity;

    protected $fillable = ['title', 'multi_select', 'is_active', 'company_id', 'branch_id', 'display', 'sort_order'];
    public $translatable = ['title', 'display'];
    protected static $logName = 'Addon';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;


    public function values()
    {
        return $this->hasMany(AddonValue::class, 'addon_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'addon_product');
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    protected function scopeAvailable($query)
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
