<?php

namespace Modules\Service\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Service\DTOs\ServiceDto;
use Modules\Service\Models\Service;

class ServiceService
{
    use UploaderHelper;

    public function __construct(private Service $model) {}

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Service
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Service $serviceOrId): Service
    {
        return $serviceOrId instanceof Service ? $serviceOrId : $this->findById($serviceOrId);
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

    public function save(ServiceDto $dto): Service
    {
        $data = $dto->toArray();
        if ($dto->image) {
            $data['image'] = $this->uploadImage($dto->image, 'service');
        }

        return $this->model::create($data);
    }

    public function update(int|Service $serviceOrId, ServiceDto $dto): Service
    {
        $service = $this->resolveModel($serviceOrId);
        $data = $dto->toArray();
        if ($dto->image) {
            if ($service->image) {
                $this->deleteImage($service->image, 'service');
            }

            $data['image'] = $this->uploadImage($dto->image, 'service');
        }

        $service->update($data);

        return $service;
    }

    public function delete(int|Service $serviceOrId): bool
    {
        $service = $this->resolveModel($serviceOrId);
        if ($service->image) {
            $this->deleteImage($service->image, 'service');
        }

        return $service->delete();
    }

    public function activate(int|Service $serviceOrId): Service
    {
        $service = $this->resolveModel($serviceOrId);
        $service->update(['is_active' => ! $service->is_active]);

        return $service;
    }
}
