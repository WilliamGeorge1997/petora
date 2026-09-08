<?php

namespace Modules\Clinic\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Clinic\DTOs\ClinicDto;
use Modules\Clinic\Models\Clinic;
use Modules\Common\Helpers\UploaderHelper;
use Illuminate\Support\Facades\DB;

class ClinicService
{
    use UploaderHelper;

    private string $model = Clinic::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Clinic
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Clinic $clinicOrId): Clinic
    {
        return $clinicOrId instanceof Clinic ? $clinicOrId : $this->findById($clinicOrId);
    }

    public function findBy(string $column, mixed $value, array $data, array $relations = []):  LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']):  LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->active()->filter($data)->with($relations)->latest('id');;
        return getCaseCollection($query, $data, $columns);
    }
    
    public function save(ClinicDto $dto, array $workingHours = []): Clinic
    {
        return DB::transaction(function () use ($dto, $workingHours) {
            $data = $dto->toArray();
            if ($dto->image) {
                $data['image'] = $this->uploadImage($dto->image, 'clinic');
            }

            /** @var Clinic $clinic */
            $clinic = $this->model::create($data);

            if (!empty($workingHours)) {
                $this->syncWorkingHours($clinic, $workingHours);
            }

            return $clinic;
        });
    }

    public function update(int|Clinic $clinicOrId, ClinicDto $dto, ?array $workingHours = null): Clinic
    {
        $clinic = $this->resolveModel($clinicOrId);

        return DB::transaction(function () use ($clinic, $dto, $workingHours) {
            $data = $dto->toArray();
            if ($dto->image) {
                if ($clinic->image) {
                    $this->deleteImage($clinic->image, 'clinic');
                }

                $data['image'] = $this->uploadImage($dto->image, 'clinic');
            }

            $clinic->update($data);

            if ($workingHours !== null) {
                $this->syncWorkingHours($clinic, $workingHours);
            }

            return $clinic;
        });
    }

    public function syncWorkingHours(Clinic $clinic, array $workingHours): void
    {
        $days = [];

        foreach ($workingHours as $item) {
            if (empty($item['day'])) {
                continue;
            }

            $isOpen24 = (bool) ($item['is_open_24_hours'] ?? false);

            $clinic->workingHours()->updateOrCreate(
                ['day' => $item['day']],
                [
                    'is_open_24_hours' => $isOpen24,
                    'from'             => $isOpen24 ? null : ($item['from'] ?? null),
                    'to'               => $isOpen24 ? null : ($item['to'] ?? null),
                ]
            );

            $days[] = $item['day'];
        }

        $clinic->workingHours()->whereNotIn('day', $days)->delete();
    }

    public function delete(int|Clinic $clinicOrId): bool
    {
        $clinic = $this->resolveModel($clinicOrId);
        if ($clinic->image) $this->deleteImage($clinic->image, 'clinic');
        return $clinic->delete();
    }

    public function activate(int|Clinic $clinicOrId): Clinic
    {
        $clinic = $this->resolveModel($clinicOrId);
        $clinic->update(['is_active' => !$clinic->is_active]);
        return $clinic;
    }
}
