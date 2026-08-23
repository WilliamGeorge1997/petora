<?php

namespace Modules\Package\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class Package extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['title', 'description', 'price', 'discounted_price', 'image', 'is_active', 'duration_months', 'is_special', 'product_count'];
    protected $translatable = ['title', 'description'];

    protected static $logName = 'Package';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d - h:i A');
    }

    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            } else {
                return asset('uploads/package/' . $value);
            }
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeNotSpecial($query)
    {
        return $query->where('is_special', 0);
    }
}
