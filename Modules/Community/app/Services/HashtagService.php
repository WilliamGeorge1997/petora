<?php

namespace Modules\Community\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Community\DTOs\HashtagDto;
use Modules\Community\Models\Hashtag;
use Illuminate\Pagination\CursorPaginator;

class HashtagService
{
    private string $model = Hashtag::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Hashtag
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Hashtag $hashtagOrId): Hashtag
    {
        return $hashtagOrId instanceof Hashtag ? $hashtagOrId : $this->findById($hashtagOrId);
    }

    public function findBy(string $column, mixed $value, array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->active()->with($relations);
        return getCaseCollection($query, $data, $columns);
    }

    public function save(HashtagDto $dto): Hashtag
    {
        return $this->model::create($dto->toArray());
    }

    public function update(int|Hashtag $hashtagOrId, HashtagDto $dto): Hashtag
    {
        $hashtag = $this->resolveModel($hashtagOrId);
        $hashtag->update($dto->toArray());
        return $hashtag;
    }

    public function delete(int|Hashtag $hashtagOrId): bool
    {
        $hashtag = $this->resolveModel($hashtagOrId);
        return $hashtag->delete();
    }

    public function activate(int|Hashtag $hashtagOrId): Hashtag
    {
        $hashtag = $this->resolveModel($hashtagOrId);
        $hashtag->update(['is_active' => !$hashtag->is_active]);
        return $hashtag;
    }
}
