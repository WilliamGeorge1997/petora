<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Clinic\Models\Clinic;
use Modules\Service\Models\Service;

class ClinicServiceImport implements ToCollection, WithHeadingRow
{
    public function __construct(protected Clinic $clinic) {}

    public function collection(Collection $rows): void
    {
        $syncData = [];

        foreach ($rows as $row) {
            $serviceId = $row['id'] ?? null;
            if (!$serviceId) {
                continue;
            }

            $service = Service::find($serviceId);
            if ($service) {
                $price = $row['alsaar'] ?? $row['price'] ?? null;
                $duration = $row['almd_bldkayk'] ?? $row['duration'] ?? null;
                $isActive = $row['mfaal'] ?? $row['is_active'] ?? 'yes';

                $syncData[$serviceId] = [
                    'price'     => (is_numeric($price) && $price >= 0) ? (float) $price : (float) $service->price,
                    'duration'  => (is_numeric($duration) && $duration > 0) ? (int) $duration : (int) $service->duration,
                    'is_active' => in_array(strtolower((string) $isActive), ['1', 'yes', 'true', 'نعم', 'مفعل'], true) ? 1 : 0,
                ];
            }
        }

        if (!empty($syncData)) {
            $this->clinic->services()->syncWithoutDetaching($syncData);
        }
    }
}
