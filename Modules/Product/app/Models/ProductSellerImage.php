<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSellerImage extends Model
{
    use HasFactory;

    protected $table = 'product_seller_images';

    protected $fillable = [
        'product_seller_id',
        'image',
    ];

    protected $hidden = ['product_seller_id'];

    // Date serialization
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    // Getters
    public function getImageAttribute(?string $value): ?string
    {
        if ($value !== null && $value !== '') {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }

            return asset('storage/uploads/product_sellers/'.$value);
        }

        return $value;
    }

    // Relations
    public function productSeller(): BelongsTo
    {
        return $this->belongsTo(ProductSeller::class, 'product_seller_id');
    }
}
