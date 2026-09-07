<?php

namespace Modules\Community\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Community\DTOs\PostDto;
use Modules\Community\Models\Post;
use Illuminate\Pagination\CursorPaginator;

class PostService
{
    use UploaderHelper;

    private string $model = Post::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Post
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Post $postOrId): Post
    {
        return $postOrId instanceof Post ? $postOrId : $this->findById($postOrId);
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

    public function save(PostDto $dto): Post
    {
        $post = $this->model::create($dto->toArray());
        
        if ($dto->media) {
            foreach ($dto->media as $item) {
                if (isset($item['file'])) {
                    $isVideo = $item['is_video'] ?? false;
                    $mediaPath = $isVideo ? $this->uploadFile($item['file'], 'community/post') : $this->uploadImage($item['file'], 'community/post');
                    
                    $post->media()->create([
                        'media' => $mediaPath,
                        'is_video' => $isVideo,
                    ]);
                }
            }
        }
        
        return $post;
    }

    public function update(int|Post $postOrId, PostDto $dto): Post
    {
        $post = $this->resolveModel($postOrId);
        $post->update($dto->toArray());

        if ($dto->media) {
            // Delete old media
            foreach ($post->media as $postMedia) {
                if ($postMedia->media) {
                    $this->deleteImage($postMedia->getRawOriginal('media'), 'community/post');
                }
                $postMedia->delete();
            }

            // Upload new ones
            foreach ($dto->media as $item) {
                if (isset($item['file'])) {
                    $isVideo = $item['is_video'] ?? false;
                    $mediaPath = $isVideo ? $this->uploadFile($item['file'], 'community/post') : $this->uploadImage($item['file'], 'community/post');
                    
                    $post->media()->create([
                        'media' => $mediaPath,
                        'is_video' => $isVideo,
                    ]);
                }
            }
        }

        return $post;
    }

    public function delete(int|Post $postOrId): bool
    {
        $post = $this->resolveModel($postOrId);
        foreach ($post->media as $postMedia) {
            if ($postMedia->media) {
                $this->deleteImage($postMedia->getRawOriginal('media'), 'community/post');
            }
        }
        return $post->delete();
    }

    public function activate(int|Post $postOrId): Post
    {
        $post = $this->resolveModel($postOrId);
        $post->update(['is_active' => !$post->is_active]);
        return $post;
    }
}
