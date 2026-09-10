<?php

namespace Modules\Doctor\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Doctor\DTOs\DoctorDto;
use Modules\Doctor\Models\Doctor;

class DoctorService
{
    use UploaderHelper;

    public function __construct(private Doctor $model) {}

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Doctor
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Doctor $doctorOrId): Doctor
    {
        return $doctorOrId instanceof Doctor ? $doctorOrId : $this->findById($doctorOrId);
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

    public function save(DoctorDto $dto): Doctor
    {
        $data = $dto->toArray();
        if ($dto->image) {
            $data['image'] = $this->uploadImage($dto->image, 'doctor');
        }

        return $this->model::create($data);
    }

    public function update(int|Doctor $doctorOrId, DoctorDto $dto): Doctor
    {
        $doctor = $this->resolveModel($doctorOrId);
        $data = $dto->toArray();
        if ($dto->image) {
            if ($doctor->image) {
                $this->deleteImage($doctor->image, 'doctor');
            }

            $data['image'] = $this->uploadImage($dto->image, 'doctor');
        }

        $doctor->update($data);

        return $doctor;
    }

    public function delete(int|Doctor $doctorOrId): bool
    {
        $doctor = $this->resolveModel($doctorOrId);
        if ($doctor->image) {
            $this->deleteImage($doctor->image, 'doctor');
        }

        return $doctor->delete();
    }

    public function activate(int|Doctor $doctorOrId): Doctor
    {
        $doctor = $this->resolveModel($doctorOrId);
        $doctor->update(['is_active' => ! $doctor->is_active]);

        return $doctor;
    }
}
