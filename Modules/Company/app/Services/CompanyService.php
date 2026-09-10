<?php

namespace Modules\Company\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Company\DTOs\CompanyDto;
use Modules\Company\Models\Company;

class CompanyService
{
    use UploaderHelper;

    protected string $model = Company::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
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

    public function findById(int $id, array $relations = []): Company
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Company $companyOrId): Company
    {
        return $companyOrId instanceof Company ? $companyOrId : $this->findById($companyOrId);
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

    public function save(CompanyDto $dto): Company
    {
        $data = $dto->toArray();
        if ($dto->image) {
            $data['image'] = $this->uploadImage($dto->image, 'company');
        }

        return $this->model::create($data);
    }

    public function update(int|Company $companyOrId, CompanyDto $dto): Company
    {
        $company = $this->resolveModel($companyOrId);
        $data = $dto->toArray();
        if ($dto->image) {
            if ($company->image) {
                $this->deleteImage($company->image, 'company');
            }

            $data['image'] = $this->uploadImage($dto->image, 'company');
        }

        $company->update($data);

        return $company;
    }

    public function delete(int|Company $companyOrId): bool
    {
        $company = $this->resolveModel($companyOrId);
        if ($company->image) {
            $this->deleteImage($company->image, 'company');
        }

        return $company->delete();
    }

    public function activate(int|Company $companyOrId): Company
    {
        $company = $this->resolveModel($companyOrId);
        $company->update(['is_active' => ! $company->is_active]);

        return $company;
    }
}
