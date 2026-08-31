<?php

namespace Modules\Country\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Country\DTOs\ZoneDto;
use Modules\Country\Models\Zone;

class ZoneService
{
    use UploaderHelper;

    protected string $model = Zone::class;

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

    public function findById(int $id, array $relations = []): Zone
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

    protected function resolveModel(int|Zone $zoneOrId): Zone
    {
        return $zoneOrId instanceof Zone ? $zoneOrId : $this->findById($zoneOrId);
    }


    public function active(array $data = [], array $relations = [], array $columns = ['*']):  LengthAwarePaginator|Collection
    {
        $query = $this->model::query()->active()->with($relations);
        return getCaseCollection($query, $data, $columns);
    }

    public function save(ZoneDto $dto): Zone
    {
        $data = $dto->toArray();

        return $this->model::create($data);
    }

    public function update(int|Zone $zoneOrId, ZoneDto $dto): Zone
    {
        $zone = $this->resolveModel($zoneOrId);
        $data = $dto->toArray();

        $zone->update($data);
        return $zone;
    }

    public function delete(int|Zone $zoneOrId): bool
    {
        $zone = $this->resolveModel($zoneOrId);
        return $zone->delete();
    }

    public function activate(int|Zone $zoneOrId): Zone
    {
        $zone = $this->resolveModel($zoneOrId);
        $zone->update(['is_active' => !$zone->is_active]);
        return $zone;
    }
}
