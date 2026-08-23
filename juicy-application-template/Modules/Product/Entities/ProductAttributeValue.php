<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;
use Modules\Branch\Entities\BranchProductAttributeValue;

class ProductAttributeValue extends Model
{
    use HasFactory, HasTranslations, LogsActivity;

    protected $fillable = ['attribute_value', 'price', 'product_attribute_id', 'image'];
    public $translatable = ['attribute_value'];

    protected static $logName  = 'ProductAttributeValue';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;


    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            return asset('uploads/attribute_value/' . $value);
        }
        return $value;
    }
}
