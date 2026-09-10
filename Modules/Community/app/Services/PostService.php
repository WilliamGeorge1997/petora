<?php

namespace Modules\Community\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Community\DTOs\PostDto;
use Modules\Community\Models\Hashtag;
use Modules\Community\Models\Post;

class PostService
{
    use UploaderHelper;

    private string $model = Post::class;
    private string $uploadFolder = 'post';
    
    public function findAll(array $data, array $relations = [], array $counts = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->withCount($counts)
            ->withIsLiked()
            ->filter($data)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = [], array $counts = []): Post
    {
        return $this->model::with($relations)
            ->withCount($counts)
            ->withIsLiked()
            ->findOrFail($id);
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

    public function active(array $data = [], array $relations = [], array $counts = [], array $columns = ['*']): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()
            ->active()
            ->with($relations)
            ->withCount($counts)
            ->withIsLiked()
            ->latest('id');

        return getCaseCollection($query, $data, $columns);
    }

    public function save(PostDto $dto): Post
    {
        return DB::transaction(function () use ($dto) {
            $post = $this->model::create($dto->toArray());

            if ($dto->media) {
                foreach ($dto->media as $item) {
                    if (isset($item['file'])) {
                        $isVideo = $item['is_video'] ?? false;
                        $mediaPath = $isVideo ? $this->uploadFile($item['file'], $this->uploadFolder) : $this->uploadImage($item['file'], $this->uploadFolder);

                        $post->media()->create([
                            'media' => $mediaPath,
                            'is_video' => $isVideo,
                        ]);
                    }
                }
            }

            $this->syncHashtags($post, $dto->hashtags);

            return $post->load(['media', 'hashtags']);
        });
    }

    public function update(int|Post $postOrId, PostDto $dto): Post
    {
        return DB::transaction(function () use ($postOrId, $dto) {
            $post = $this->resolveModel($postOrId);
            $post->update($dto->toArray());

            if ($dto->media) {
                // Delete old media
                foreach ($post->media as $postMedia) {
                    if ($postMedia->media) {
                        $this->deleteImage($postMedia->getRawOriginal('media'), $this->uploadFolder);
                    }
                    $postMedia->delete();
                }

                // Upload new ones
                foreach ($dto->media as $item) {
                    if (isset($item['file'])) {
                        $isVideo = $item['is_video'] ?? false;
                        $mediaPath = $isVideo ? $this->uploadFile($item['file'], $this->uploadFolder) : $this->uploadImage($item['file'], $this->uploadFolder);

                        $post->media()->create([
                            'media' => $mediaPath,
                            'is_video' => $isVideo,
                        ]);
                    }
                }
            }

            $this->syncHashtags($post, $dto->hashtags);

            return $post->load(['media', 'hashtags']);
        });
    }

    public function syncHashtags(Post $post, ?array $hashtags): void
    {
        if ($hashtags === null) {
            return;
        }

        $hashtagIds = [];
        foreach ($hashtags as $tag) {
            if (! is_string($tag)) {
                continue;
            }

            $hashtag = Hashtag::firstOrCreate(
                ['text' => $tag],
                ['is_active' => true]
            );

            $hashtagIds[] = $hashtag->id;
        }

        $post->hashtags()->sync(array_unique($hashtagIds));
    }

    public function delete(int|Post $postOrId): bool
    {
        $post = $this->resolveModel($postOrId);
        foreach ($post->media as $postMedia) {
            if ($postMedia->media) {
                $this->deleteImage($postMedia->getRawOriginal('media'), $this->uploadFolder);
            }
        }

        return $post->delete();
    }

    public function activate(int|Post $postOrId): Post
    {
        $post = $this->resolveModel($postOrId);
        $post->update(['is_active' => ! $post->is_active]);

        return $post;
    }

    // Helpers
    public function postRelations(bool $includeComments = true, ?int $commentsLimit = null, ?int $repliesLimit = null): array
    {
        $relations = [
            'client:id,name,phone,email,image',
            'media',
            'hashtags' => fn($query) => $query->active(),
        ];

        if ($includeComments) {
            $relations['comments'] = fn($query) => $query->active()
                ->whereNull('parent_id')
                ->latest('id')
                ->when($commentsLimit, fn($q) => $q->limit($commentsLimit))
                ->with([
                    'client:id,name,phone,email,image',
                    'replies' => fn($q) => $q->active()
                        ->latest('id')
                        ->when($repliesLimit, fn($rq) => $rq->limit($repliesLimit))
                        ->with('client:id,name,phone,email,image')
                        ->withCount('likes')
                        ->withIsLiked(),
                ])
                ->withCount([
                    'likes',
                    'replies' => fn($q) => $q->active(),
                ])
                ->withIsLiked();
        }

        return $relations;
    }

    public function postCounts(): array
    {
        return [
            'likes',
            'comments' => fn($query) => $query->active()->whereNull('parent_id'),
        ];
    }
}
