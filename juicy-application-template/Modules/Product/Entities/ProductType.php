<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\Traits\LogsActivity;
use Modules\Branch\Entities\BranchProductType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

final class ProductType extends Model
{
    use HasFactory, HasTranslations, LogsActivity;

    protected $fillable = ['product_id', 'title', 'price', 'image', 'sort_order', 'is_active'];
    public $translatable = ['title'];

    protected static $logName = 'ProductType';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;

    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d h:i A');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function branchProductTypes()
    {
        return $this->hasMany(BranchProductType::class);
    }
}
