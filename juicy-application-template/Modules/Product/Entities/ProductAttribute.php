<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Branch\Entities\BranchProductAttributeValue;

class ProductAttribute extends Model
{
    use HasFactory, HasTranslations, LogsActivity;

    protected $fillable = ['title', 'required', 'multi_select', 'override_price', 'product_id', 'is_active'];
    public $translatable = ['title'];

    protected static $logName  = 'ProductAttribute';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    //Relations
    public function values()
    {
        return $this->hasMany(ProductAttributeValue::class, 'product_attribute_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
