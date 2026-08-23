<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Entities\Addon;
use Modules\Product\Entities\AddonValue;

class OrderDetailsAddon extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'order_details_id', 'product_id', 'addon_id', 'addon_value_id'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function addon()
    {
        return $this->belongsTo(Addon::class, 'addon_id');
    }

    public function addonValue()
    {
        return $this->belongsTo(AddonValue::class, 'addon_value_id');
    }
}
