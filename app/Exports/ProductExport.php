<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Product\Models\Product;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    protected $seller;

    public function __construct($seller)
    {
        $this->seller = $seller;
    }

    public function collection()
    {
        return $this->seller->products;
    }

    public function headings(): array
    {
        return [
            'id',
            'category_id',
            'title_en',
            'title_ar',
            'price_pivot',
            'is_active_pivot',
            'created_at',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->category_id,
            $product->getTranslation('title', 'en', false),
            $product->getTranslation('title', 'ar', false),
            $product->pivot->price,
            $product->pivot->is_active ? 'Yes' : 'No',
            $product->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
