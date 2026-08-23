<?php

namespace Modules\Order\Entities;

use Modules\Order\Entities\Order;
use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\ProductType;
use Modules\Order\Entities\OrderDetailsSide;
use Modules\Order\Entities\OrderDetailsAddon;
use Modules\Order\Entities\OrderDetailsAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'price',
        'quantity',
        'order_id',
        'product_id',
        'product_type_id',
        'note',
        'product_price',
        'product_type_price'
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function attributes()
    {
        return $this->hasMany(OrderDetailsAttribute::class);
    }

    public function addons()
    {
        return $this->hasMany(OrderDetailsAddon::class);
    }

    public function sides()
    {
        return $this->hasMany(OrderDetailsSide::class);
    }
}
