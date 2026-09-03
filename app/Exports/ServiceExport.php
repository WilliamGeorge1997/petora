<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Service\Models\Service;

class ServiceExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        return Service::query()->active()->latest('id')->get(['id', 'title', 'price', 'duration']);
    }

    public function headings(): array
    {
        return [
            'id',
            'الاسم بالعربية',
            'الاسم بالانجليزية',
            'السعر',
            'المدة (بالدقائق)',
            'مفعل',
        ];
    }

    public function map($service): array
    {
        return [
            $service->id,
            $service->getTranslation('title', 'ar', false),
            $service->getTranslation('title', 'en', false),
            $service->price,
            $service->duration,
            'yes',
        ];
    }
}
