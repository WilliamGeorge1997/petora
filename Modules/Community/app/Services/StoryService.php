<?php

namespace Modules\Community\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\CursorPaginator;
use Modules\Client\Models\Client;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Community\DTOs\StoryDto;
use Modules\Community\Models\Follow;
use Modules\Community\Models\Story;

class StoryService
{
    use UploaderHelper;

    private string $model = Story::class;

    public function findAll(array $data, array $relations = [], array $counts = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()
            ->with($relations)
            ->withCount($counts)
            ->withIsLiked()
            ->filter($data)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = [], array $counts = []): Story
    {
        return $this->model::with($relations)
            ->withCount($counts)
            ->withIsLiked()
            ->findOrFail($id);
    }

    protected function resolveModel(int|Story $storyOrId): Story
    {
        return $storyOrId instanceof Story ? $storyOrId : $this->findById($storyOrId);
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

    public function feed(array $data = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $authId = auth('client')->id();
        $query = Client::selectRaw('id, name, image, (id = ?) as is_me', [$authId])
            ->whereHas('stories', fn ($q) => $q->active())
            ->withCount(['stories' => fn ($q) => $q->active()])
            ->where(fn ($q) => $q
                ->whereIn('id', Follow::select('following_id')->where('follower_id', $authId))
                ->orWhere('id', $authId)
            )
            ->orderByDesc('is_me')
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function save(StoryDto $dto): Story
    {
        $data = $dto->toArray();
        if ($dto->media instanceof UploadedFile) {
            $data['media'] = $dto->isVideo
                ? $this->uploadFile($dto->media, 'community/story')
                : $this->uploadImage($dto->media, 'community/story');
        }
        return $this->model::create($data);
    }

    public function delete(int|Story $storyOrId): bool
    {
        $story = $this->resolveModel($storyOrId);
        if ($story->media) {
            $this->deleteImage($story->getRawOriginal('media'), 'community/story');
        }
        return $story->delete();
    }

    public function activate(int|Story $storyOrId): Story
    {
        $story = $this->resolveModel($storyOrId);
        $story->update(['is_active' => !$story->is_active]);
        return $story;
    }

    //Helpers
    public function storyCounts(): array
    {
        return [
            'likes',
        ];
    }
}
