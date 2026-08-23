<?php

namespace Modules\Product\Entities;

use Modules\Product\Entities\Side;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SideValue extends Model
{
    use HasFactory, HasTranslations, LogsActivity;

    protected $fillable = ['title', 'side_id', 'image', 'is_active'];
    public $translatable = ['title'];

    protected static $logName = 'SideValue';
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
            return asset('uploads/sides/' . $value);
        }
        return $value;
    }
    //Relations
    public function side()
    {
        return $this->belongsTo(Side::class, 'side_id');
    }

    //Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
