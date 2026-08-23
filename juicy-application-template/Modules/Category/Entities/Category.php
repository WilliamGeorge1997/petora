<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Entities\Branch;
use Modules\Order\Entities\OrderDetails;
use Modules\Product\Entities\Product;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasTranslations, LogsActivity;

    protected $fillable = ['title', 'is_active', 'category_id', 'image', 'sort_order', 'branch_id'];
    public $translatable = ['title'];
    protected static $logName = 'Category';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeMain($query)
    {
        return $query->where('is_active', 1)->whereNull('category_id');
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function childs()
    {
        return $this->hasMany(Category::class, 'category_id', 'id');
    }

    public function childrenRecursive()
    {
        return $this->childs()->with('childrenRecursive');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function parentRecursive()
    {
        return $this->parent()->with('parentRecursive');
    }

    public function orderDetails()
    {
        return $this->hasManyThrough(orderDetails::class, Product::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            return asset('uploads/category/' . $value);
        }
        return $value;
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
