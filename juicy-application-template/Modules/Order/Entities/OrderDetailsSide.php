<?php

namespace Modules\Order\Entities;

use Modules\Product\Entities\Side;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\SideValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDetailsSide extends Model
{
    use HasFactory;

    protected $fillable = ['order_details_id', 'product_id', 'side_id', 'side_value_id'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function side()
    {
        return $this->belongsTo(Side::class, 'side_id');
    }

    public function sideValue()
    {
        return $this->belongsTo(SideValue::class, 'side_value_id');
    }
}
