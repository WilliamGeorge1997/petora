<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Product\Models\Product;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        return Product::query()->active()->latest('id')->get(['id', 'title', 'price', 'is_active']);
    }

    public function headings(): array
    {
        return [
            'id',
            'name_en',
            'name_ar',
            'price',
            'is_active',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->getTranslation('title', 'en', false),
            $product->getTranslation('title', 'ar', false),
            $product->price,
            $product->is_active,
        ];
    }
}
