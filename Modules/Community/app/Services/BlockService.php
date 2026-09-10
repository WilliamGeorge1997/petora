<?php

namespace Modules\Community\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Community\DTOs\BlockDto;
use Modules\Community\Models\Block;

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

    public function firstBy(array $conditions, array $relations = []): ?Block
    {
        return $this->model::with($relations)->where($conditions)->first();
    }

    public function toggleBlock(int $blocker_id, int $blocked_id): ?Block
    {
        $block = $this->firstBy([
            'blocker_id' => $blocker_id,
            'blocked_id' => $blocked_id,
        ]);

        if ($block) {
            $this->delete($block);

            return null;
        }

        $dto = new BlockDto($blocker_id, $blocked_id);

        return $this->save($dto);
    }
}
