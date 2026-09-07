<?php

namespace Modules\Community\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Community\DTOs\BlockDto;
use Modules\Community\Models\Block;
use Illuminate\Pagination\CursorPaginator;

class BlockService
{
    private string $model = Block::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Block
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Block $blockOrId): Block
    {
        return $blockOrId instanceof Block ? $blockOrId : $this->findById($blockOrId);
    }

    public function save(BlockDto $dto): Block
    {
        return $this->model::create($dto->toArray());
    }

    public function delete(int|Block $blockOrId): bool
    {
        return $this->resolveModel($blockOrId)->delete();
    }
}
