<?php

namespace Modules\Community\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Community\DTOs\CommentDto;
use Modules\Community\Models\Comment;
use Illuminate\Pagination\CursorPaginator;

class CommentService
{
    private string $model = Comment::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Comment
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Comment $commentOrId): Comment
    {
        return $commentOrId instanceof Comment ? $commentOrId : $this->findById($commentOrId);
    }

    public function save(CommentDto $dto): Comment
    {
        return $this->model::create($dto->toArray());
    }

    public function update(int|Comment $commentOrId, CommentDto $dto): Comment
    {
        $comment = $this->resolveModel($commentOrId);
        $comment->update($dto->toArray());
        return $comment;
    }

    public function delete(int|Comment $commentOrId): bool
    {
        return $this->resolveModel($commentOrId)->delete();
    }

    public function activate(int|Comment $commentOrId): Comment
    {
        $comment = $this->resolveModel($commentOrId);
        $comment->update(['is_active' => !$comment->is_active]);
        return $comment;
    }
}
