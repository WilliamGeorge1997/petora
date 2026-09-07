<?php

namespace Modules\Community\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Community\DTOs\FollowDto;
use Modules\Community\Models\Follow;
use Illuminate\Pagination\CursorPaginator;

class FollowService
{
    private string $model = Follow::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Follow
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Follow $followOrId): Follow
    {
        return $followOrId instanceof Follow ? $followOrId : $this->findById($followOrId);
    }

    public function save(FollowDto $dto): Follow
    {
        return $this->model::create($dto->toArray());
    }

    public function delete(int|Follow $followOrId): bool
    {
        return $this->resolveModel($followOrId)->delete();
    }
}
