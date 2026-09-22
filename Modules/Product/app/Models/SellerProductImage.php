<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_product_id',
        'image',
    ];

    protected $hidden = ['seller_product_id'];

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

            return asset('storage/uploads/seller_products/'.$value);
        }

        return $value;
    }

    // Relations
    public function sellerProduct(): BelongsTo
    {
        return $this->belongsTo(SellerProduct::class);
    }
}
