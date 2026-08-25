<?php

namespace Modules\Company\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Company\DTOs\CompanyDto;
use Modules\Company\Models\Company;

class CompanyService
{
    use UploaderHelper;

    public function __construct(private Company $model) {}


    public function findAll(array $data, array $relations = []): LengthAwarePaginator|Collection
    {
        $query = $this->model::query()->with($relations)->latest();
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Company
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    public function findBy(string $column, mixed $value, array $data, array $relations = []): Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function save(CompanyDto $dto): Company
    {
        $data = $dto->toArray();
        if ($dto->image) {
            $data['image'] = $this->uploadImage($dto->image, 'company');
        }
        
        return $this->model::create($data);
    }

    public function update(Company $company, CompanyDto $dto): Company
    {
        $data = $dto->toArray();
        if ($dto->image) {
            if ($company->image) $this->deleteImage($company->image, 'company');

            $data['image'] = $this->uploadImage($dto->image, 'company');
        }

        $company->update($data);
        return $company;
    }

    public function delete(Company $company): bool
    {
        if ($company->image) $this->deleteImage($company->image, 'company');
        return $company->delete();
    }

    public function activate(Company $company): Company
    {
        $company->update(['is_active' => !$company->is_active]);
        return $company;
    }
}
