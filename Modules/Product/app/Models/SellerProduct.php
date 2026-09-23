<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Clinic\Models\Clinic;
use Modules\Store\Models\Store;
use Spatie\Translatable\HasTranslations;

class SellerProduct extends Pivot
{
    use HasTranslations;

    protected $table = 'seller_product';

    protected $fillable = [
        'product_id',
        'store_id',
        'clinic_id',
        'title',
        'description',
        'price',
        'is_active',
    ];

    public array $translatable = ['title', 'description'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Date serialization
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d h:i A');
    }

    // Relations
    public function images(): HasMany
    {
        return $this->hasMany(SellerProductImage::class, 'seller_product_id');
    }

    public function firstImage(): HasOne
    {
        return $this->hasOne(SellerProductImage::class, 'seller_product_id')->oldest('id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
