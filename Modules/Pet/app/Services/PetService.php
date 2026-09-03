<?php

namespace Modules\Pet\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Pet\Classes\DTOs\PetDto;
use Modules\Pet\Models\Pet;
use Illuminate\Pagination\CursorPaginator;

class PetService
{
    use UploaderHelper;

    private string $model = Pet::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Pet
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Pet $petOrId): Pet
    {
        return $petOrId instanceof Pet ? $petOrId : $this->findById($petOrId);
    }

    public function findBy(string $column, mixed $value, array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function save(PetDto $dto): Pet
    {
        $data = $dto->toArray();
        if ($dto->image) {
            $data['image'] = $this->uploadImage($dto->image, 'pet');
        }

        return $this->model::create($data);
    }

    public function update(int|Pet $petOrId, PetDto $dto): Pet
    {
        $pet = $this->resolveModel($petOrId);
        $data = $dto->toArray();
        if ($dto->image) {
            if ($pet->image) $this->deleteImage($pet->image, 'pet');

            $data['image'] = $this->uploadImage($dto->image, 'pet');
        }

        $pet->update($data);
        return $pet;
    }

    public function delete(int|Pet $petOrId): bool
    {
        $pet = $this->resolveModel($petOrId);
        if ($pet->image) $this->deleteImage($pet->image, 'pet');
        return $pet->delete();
    }
}
