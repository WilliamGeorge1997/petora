<?php

namespace Modules\Country\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Country\DTOs\CountryDto;
use Modules\Country\Models\Country;

class CountryService
{
    use UploaderHelper;

    protected string $model = Country::class;

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

    public function findById(int $id, array $relations = []): Country
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Country $countryOrId): Country
    {
        return $countryOrId instanceof Country ? $countryOrId : $this->findById($countryOrId);
    }

    public function findBy(string $column, mixed $value, array $data, array $relations = []):  LengthAwarePaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']):  LengthAwarePaginator|Collection
    {
        $query = $this->model::query()->active()->with($relations);
        return getCaseCollection($query, $data, $columns);
    }

    public function save(CountryDto $dto): Country
    {
        $data = $dto->toArray();

        return $this->model::create($data);
    }

    public function update(int|Country $countryOrId, CountryDto $dto): Country
    {
        $country = $this->resolveModel($countryOrId);
        $data = $dto->toArray();

        $country->update($data);
        return $country;
    }

    public function delete(int|Country $countryOrId): bool
    {
        $country = $this->resolveModel($countryOrId);
        return $country->delete();
    }

    public function activate(int|Country $countryOrId): Country
    {
        $country = $this->resolveModel($countryOrId);
        $country->update(['is_active' => !$country->is_active]);
        return $country;
    }
}
