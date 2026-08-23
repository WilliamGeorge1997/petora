<?php

namespace Modules\Branch\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Registration extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'theme',
        'is_order_enabled',
        'manager_name',
        'manager_phone',
        'location_url',
        'working_from',
        'working_to',
        'is_working_24_hours',
        'is_completed',
        'image',
    ];

    protected $translatable = ['name', 'address'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }


    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            return asset('uploads/registration/' . $value);
        }
        return $value;
    }
}
