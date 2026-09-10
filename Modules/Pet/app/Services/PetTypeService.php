<?php

namespace Modules\Pet\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Pet\DTOs\PetTypeDto;
use Modules\Pet\Models\PetType;
use Illuminate\Pagination\CursorPaginator;

class PetTypeService
{
    private string $model = PetType::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): PetType
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|PetType $petTypeOrId): PetType
    {
        return $petTypeOrId instanceof PetType ? $petTypeOrId : $this->findById($petTypeOrId);
    }

    public function findBy(string $column, mixed $value, array $data, array $relations = []):  LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']):  LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->active()->with($relations);
        return getCaseCollection($query, $data, $columns);
    }
    
    public function save(PetTypeDto $dto): PetType
    {
        $data = $dto->toArray();
        return $this->model::create($data);
    }

    public function update(int|PetType $petTypeOrId, PetTypeDto $dto): PetType
    {
        $petType = $this->resolveModel($petTypeOrId);
        $data = $dto->toArray();
        
        $petType->update($data);
        return $petType;
    }

    public function delete(int|PetType $petTypeOrId): bool
    {
        $petType = $this->resolveModel($petTypeOrId);
        return $petType->delete();
    }

    public function activate(int|PetType $petTypeOrId): PetType
    {
        $petType = $this->resolveModel($petTypeOrId);
        $petType->update(['is_active' => !$petType->is_active]);
        return $petType;
    }
}
