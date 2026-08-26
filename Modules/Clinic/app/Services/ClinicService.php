<?php

namespace Modules\Clinic\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Clinic\DTOs\ClinicDto;
use Modules\Clinic\Models\Clinic;

class ClinicService
{
    use UploaderHelper;

    public function __construct(private Clinic $model) {}

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->when($data['title'] ?? null, function (Builder $query) use ($data) {
                return $query->whereJsonContainsLocales('title', ['en', 'ar'], "%{$data['title']}%", 'LIKE');
            })
            ->when(isset($data['is_active']) && $data['is_active'] !== '', function (Builder $query) use ($data) {
                return $query->where('is_active', (bool) $data['is_active']);
            })
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Clinic
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    public function findBy(string $column, mixed $value, array $data, array $relations = []): Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']): Collection
    {
        $query = $this->model::query()->active()->with($relations);
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

    public function update(Clinic $clinic, ClinicDto $dto): Clinic
    {
        $data = $dto->toArray();
        if ($dto->image) {
            if ($clinic->image) $this->deleteImage($clinic->image, 'clinic');

            $data['image'] = $this->uploadImage($dto->image, 'clinic');
        }

        $clinic->update($data);
        return $clinic;
    }

    public function delete(Clinic $clinic): bool
    {
        if ($clinic->image) $this->deleteImage($clinic->image, 'clinic');
        return $clinic->delete();
    }

    public function activate(Clinic $clinic): Clinic
    {
        $clinic->update(['is_active' => !$clinic->is_active]);
        return $clinic;
    }
}
