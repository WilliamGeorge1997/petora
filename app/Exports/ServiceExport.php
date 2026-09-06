<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Modules\Service\Models\Service;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServiceExport implements FromCollection, WithColumnWidths, WithHeadings, WithMapping, WithStyles
{
    public function collection(): Collection
    {
        return Service::query()->active()->latest('id')->get(['id', 'title', 'price', 'duration', 'is_active']);
    }

    public function headings(): array
    {
        return [
            'id',
            'name_en',
            'name_ar',
            'price',
            'duration',
            'is_active',
        ];
    }

    public function map($service): array
    {
        return [
            $service->id,
            $service->getTranslation('title', 'en', false),
            $service->getTranslation('title', 'ar', false),
            $service->price,
            $service->duration,
            $service->is_active,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 30,
            'C' => 30,
            'D' => 15,
            'E' => 15,
            'F' => 15,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            $sheet->calculateWorksheetDimension() => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            1 => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'D3D3D3',
                    ],
                ],
            ],
        ];
    }
}
