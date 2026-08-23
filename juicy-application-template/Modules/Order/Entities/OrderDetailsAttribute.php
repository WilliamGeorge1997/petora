<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\ProductAttributeValue;

class OrderDetailsAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'order_details_id', 'product_id', 'product_attribute_id', 'product_attribute_value_id'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function attribute(){
        return $this->belongsTo(ProductAttribute::class,'product_attribute_id');
    }

    public function attributeValue(){
        return $this->belongsTo(ProductAttributeValue::class,'product_attribute_value_id');
    }
}
