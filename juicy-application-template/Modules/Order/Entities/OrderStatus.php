<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class OrderStatus extends Model
{
    const SENT_TO_BRANCH = 1;
    const ACCEPTED_AND_PREPARING = 2;
    const ORDER_READY = 3;
    const ORDER_IN_DELIVERY = 4;
    const DONE = 5;
    const FAIL = 6;
    const CANCELLED = 7;

    use HasFactory, HasTranslations;

    protected $fillable = ['title'];
    public $translatable = ['title'];

    protected $table = "order_statuses";
    public $hidden = ['updated_at'];


    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }
}
