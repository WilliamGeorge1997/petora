<?php

namespace Modules\Community\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Community\DTOs\LikeDto;
use Modules\Community\Models\Like;

class LikeService
{
    private string $model = Like::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Like
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Like $likeOrId): Like
    {
        return $likeOrId instanceof Like ? $likeOrId : $this->findById($likeOrId);
    }

    public function save(LikeDto $dto): Like
    {
        return $this->model::create($dto->toArray());
    }

    public function delete(int|Like $likeOrId): bool
    {
        return $this->resolveModel($likeOrId)->delete();
    }

    public function firstBy(array $conditions, array $relations = []): ?Like
    {
        return $this->model::with($relations)->where($conditions)->first();
    }

    public function toggle(LikeDto $dto): ?Like
    {
        $like = $this->firstBy([
            'client_id' => $dto->clientId,
            'likeable_id' => $dto->likeableId,
            'likeable_type' => $dto->likeableType,
        ]);

        if ($like) {
            $this->delete($like);

            return null;
        }

        return $this->save($dto);
    }
}
