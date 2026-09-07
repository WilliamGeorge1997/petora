<?php

namespace Modules\Clinic\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Clinic\DTOs\ClinicServiceDto;
use Modules\Clinic\Models\Clinic;
use Modules\Service\Models\ClinicService;
use Modules\Service\Models\Service;
use Modules\Service\Services\ServiceService;

class ClinicServiceService
{
    private string $model = ClinicService::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): ClinicService
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|ClinicService $clinicServiceOrId): ClinicService
    {
        return $clinicServiceOrId instanceof ClinicService ? $clinicServiceOrId : $this->findById($clinicServiceOrId);
    }

    public function findBy(string $column, mixed $value, array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->active()->with($relations);
        return getCaseCollection($query, $data, $columns);
    }

    public function importAll(Clinic $clinic): void
    {
        $activeServices = (new ServiceService(new Service()))->active();
        $syncData = [];

        foreach ($activeServices as $service) {
            $syncData[$service->id] = [
                'price'     => (float) $service->price,
                'duration'  => $service->duration !== null ? (int) $service->duration : null,
                'is_active' => true,
            ];
        }

        if (!empty($syncData)) {
            $clinic->services()->syncWithoutDetaching($syncData);
        }
    }

    public function update(int|ClinicService $clinicServiceOrId, ClinicServiceDto $dto): ClinicService
    {
        $clinicService = $this->resolveModel($clinicServiceOrId);
        $clinicService->update($dto->toArray());
        return $clinicService;
    }

    public function delete(int|ClinicService $clinicServiceOrId): bool
    {
        $clinicService = $this->resolveModel($clinicServiceOrId);
        return $clinicService->delete();
    }

    public function activate(int|ClinicService $clinicServiceOrId): ClinicService
    {
        $clinicService = $this->resolveModel($clinicServiceOrId);
        $clinicService->update(['is_active' => !$clinicService->is_active]);
        return $clinicService;
    }
}
