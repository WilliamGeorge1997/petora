<?php

namespace Modules\Country\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Country\DTOs\CityDto;
use Modules\Country\Models\City;

class CityService
{
    use UploaderHelper;

    protected string $model = City::class;

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

    public function findById(int $id, array $relations = []): City
    {
        return $this->model::with($relations)->findOrFail($id);
    }


    public function findBy(string $column, mixed $value, array $data, array $relations = []):  LengthAwarePaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function findByConditions(array $conditions, array $data = [], array $relations = []): LengthAwarePaginator|Collection
    {
        $query = $this->model::query()->with($relations);

        foreach ($conditions as $column => $value) {
            $query->where($column, $value);
        }

        return getCaseCollection($query, $data);
    }

    protected function resolveModel(int|City $cityOrId): City
    {
        return $cityOrId instanceof City ? $cityOrId : $this->findById($cityOrId);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']):  LengthAwarePaginator|Collection
    {
        $query = $this->model::query()->active()->with($relations);
        return getCaseCollection($query, $data, $columns);
    }

    public function save(CityDto $dto): City
    {
        $data = $dto->toArray();

        return $this->model::create($data);
    }

    public function update(int|City $cityOrId, CityDto $dto): City
    {
        $city = $this->resolveModel($cityOrId);
        $data = $dto->toArray();

        $city->update($data);
        return $city;
    }

    public function delete(int|City $cityOrId): bool
    {
        $city = $this->resolveModel($cityOrId);
        return $city->delete();
    }

    public function activate(int|City $cityOrId): City
    {
        $city = $this->resolveModel($cityOrId);
        $city->update(['is_active' => !$city->is_active]);
        return $city;
    }
}
