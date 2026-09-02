<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Product\Models\Product;

class SellerProductImport implements ToCollection, WithHeadingRow
{
    protected $seller;

    public function __construct($seller)
    {
        $this->seller = $seller;
    }

    public function collection(Collection $rows)
    {
        $syncData = [];

        foreach ($rows as $row) {
            $productId = $row['id'] ?? null;
            if (!$productId) {
                continue;
            }

            // Check if product exists
            if (Product::find($productId)) {
                $syncData[$productId] = [
                    'price' => $row['price_pivot'] ?? 0,
                    'is_active' => strtolower($row['is_active_pivot'] ?? 'yes') === 'yes' ? 1 : 0,
                ];
            }
        }

        if (!empty($syncData)) {
            // using syncWithoutDetaching to not drop existing products if not in excel, 
            // but the user might want a full sync. Let's use syncWithoutDetaching by default.
            $this->seller->products()->syncWithoutDetaching($syncData);
        }
    }
}
