<?php

namespace Modules\Clinic\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Clinic\DTOs\ClinicDto;
use Modules\Clinic\Models\Clinic;
use Modules\Common\Helpers\UploaderHelper;

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
    
    public function save(ClinicDto $dto): Clinic
    {
        $data = $dto->toArray();
        if ($dto->image) {
            $data['image'] = $this->uploadImage($dto->image, 'clinic');
        }

        return $this->model::create($data);
    }

    public function update(int|Clinic $clinicOrId, ClinicDto $dto): Clinic
    {
        $clinic = $this->resolveModel($clinicOrId);
        $data = $dto->toArray();
        if ($dto->image) {
            if ($clinic->image) $this->deleteImage($clinic->image, 'clinic');

            $data['image'] = $this->uploadImage($dto->image, 'clinic');
        }

        $clinic->update($data);
        return $clinic;
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
