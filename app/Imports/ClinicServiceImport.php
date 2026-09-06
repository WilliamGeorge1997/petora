<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Modules\Clinic\Models\Clinic;
use Modules\Service\Models\ClinicService;

class ClinicServiceImport implements ToModel, WithHeadingRow, WithUpserts, WithUpsertColumns, SkipsEmptyRows
{
    public function __construct(protected Clinic $clinic) {}

    public function model(array $row): ?ClinicService
    {
        if (empty($row['id'])) {
            return null;
        }

        return new ClinicService([
            'clinic_id'  => $this->clinic->id,
            'service_id' => $row['id'],
            'price'      => $row['price'],
            'duration'   => $row['duration'] ?? null,
            'is_active'  => $row['is_active'],
        ]);
    }

    public function uniqueBy(): array
    {
        return ['clinic_id', 'service_id'];
    }

    public function upsertColumns(): array
    {
        return ['price', 'duration', 'is_active'];
    }
}
