<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class History extends Model
{
    use HasFactory;

    protected $fillable = ['notes','order_status_id','order_id','historible_id','historible_type'];

    public function historible()
    {
        return $this->morphTo();
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function status()
    {
        return $this->belongsTo(OrderStatus::class,'order_status_id');
    }
}
