<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Product\Models\Product;
use Illuminate\Support\Collection;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{

    public function collection(): Collection
    {
        return Product::query()->active()->latest('id')->get(['id', 'title', 'price']);
    }

    public function headings(): array
    {
        return [
            'id',
            'الاسم بالعربية',
            'الاسم بالانجيليزية',
            'السعر',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->getTranslation('title', 'en', false),
            $product->getTranslation('title', 'ar', false),
            $product->price,
        ];
    }
}
