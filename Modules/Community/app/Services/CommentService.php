<?php

namespace Modules\Community\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Community\DTOs\CommentDto;
use Modules\Community\Models\Comment;
use Modules\Community\Models\Post;

class CommentService
{
    private string $model = Comment::class;

    public function findAll(array $data, array $relations = [], array $counts = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->withCount($counts)
            ->withIsLiked()
            ->filter($data)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], array $relations = [], array $counts = [], array $columns = ['*']): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()
            ->active()
            ->with($relations)
            ->withCount($counts)
            ->withIsLiked()
            ->filter($data)
            ->latest('id');

        return getCaseCollection($query, $data, $columns);
    }

    public function findById(int $id, array $relations = [], array $counts = []): Comment
    {
        return $this->model::with($relations)
            ->withCount($counts)
            ->withIsLiked()
            ->findOrFail($id);
    }

    protected function resolveModel(int|Comment $commentOrId): Comment
    {
        return $commentOrId instanceof Comment ? $commentOrId : $this->findById($commentOrId);
    }

    public function save(Post $post, CommentDto $dto): Comment
    {
        $data = array_merge($dto->toArray(), ['post_id' => $post->id]);
        return $this->model::create($data);
    }

    public function reply(Comment $parent, CommentDto $dto): Comment
    {
        $data = array_merge($dto->toArray(), [
            'post_id'   => $parent->post_id,
            'parent_id' => $parent->id,
        ]);
        return $this->model::create($data);
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
