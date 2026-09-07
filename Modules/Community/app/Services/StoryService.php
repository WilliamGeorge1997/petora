<?php

namespace Modules\Community\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Community\DTOs\StoryDto;
use Modules\Community\Models\Story;
use Illuminate\Pagination\CursorPaginator;

class StoryService
{
    use UploaderHelper;

    private string $model = Story::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Story
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Story $storyOrId): Story
    {
        return $storyOrId instanceof Story ? $storyOrId : $this->findById($storyOrId);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->active()->with($relations);
        return getCaseCollection($query, $data, $columns);
    }

    public function save(StoryDto $dto): Story
    {
        $data = $dto->toArray();
        if ($dto->media instanceof UploadedFile) {
            $data['media'] = $this->uploadImage($dto->media, 'community/story');
        } elseif (is_string($dto->media)) {
            $data['media'] = $dto->media;
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
}
