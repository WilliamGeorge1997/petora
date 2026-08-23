<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Modules\Branch\Entities\Branch;
use Modules\Category\Entities\Category;
use Modules\Order\Entities\OrderDetails;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductImage;
use Modules\Product\Entities\Side;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;


class Product extends Model
{
    use HasFactory, HasTranslations, LogsActivity;


    protected $fillable = ['title', 'description', 'price', 'discounted_price', 'is_active', 'category_id', 'sort_order', 'branch_id', 'is_spicy', 'is_vegetarian', 'calories', 'allergens', 'multi_select_sides'];
    public $translatable = ['title', 'description', 'allergens'];
    protected $appends = ['first_image', 'last_image'];

    protected static $logName = 'Product';
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


    //Relations
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class)->where('is_active', 1)->orderBy('addons.sort_order');
    }

    public function sides()
    {
        return $this->belongsToMany(Side::class)->where('is_active', 1)->orderBy('sides.sort_order');
    }

    public function types()
    {
        return $this->hasMany(ProductType::class)->orderBy('sort_order');
    }

    public function getFirstImageAttribute()
    {
        return $this->hasOne(ProductImage::class)->orderBy('id', 'asc');
    }

    public function getLastImageAttribute()
    {
        return $this->hasOne(ProductImage::class)->latest();
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
