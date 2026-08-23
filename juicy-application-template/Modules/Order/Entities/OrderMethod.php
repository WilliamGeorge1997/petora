<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class OrderMethod extends Model
{
    const RECEIPT_FROM_BRANCH = 1;
    const RECEIPT_IN_CAR = 2;
    const RECEIPT_IN_HOME = 3;

    use HasFactory, HasTranslations;

    protected $fillable = ['title', 'is_active', 'image'];
    public $translatable = ['title'];
    protected $hidden = ['pivot', 'updated_at'];


    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            return asset('uploads/Ordermethod/' . $value);
        }
        return $value;
    }
}
