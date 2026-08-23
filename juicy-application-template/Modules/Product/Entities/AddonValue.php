<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Translatable\HasTranslations;

class AddonValue extends Model
{
    use HasFactory, HasTranslations, LogsActivity;

    protected $fillable = ['addon_id', 'title', 'price', 'image'];
    public $translatable = ['title'];


    protected static $logName  = 'AddonValue';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;

    //Serialize Date
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    //Get Image Attribute
    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            return asset('uploads/addons/' . $value);
        }
        return $value;
    }

    //Relations
    public function addon()
    {
        return $this->belongsTo(Addon::class, 'addon_id');
    }

}
