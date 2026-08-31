<?php

namespace Modules\Category\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Category\DTOs\CategoryDto;
use Modules\Category\Models\Category;

class CategoryService
{
    use UploaderHelper;

    public function __construct(private Category $model) {}

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->when($data['title'] ?? null, function (Builder $query) use ($data) {
                return $query->whereJsonContainsLocales('title', ['en', 'ar'], "%{$data['title']}%", 'LIKE');
            })
            ->when(isset($data['is_active']) && $data['is_active'] !== '', function (Builder $query) use ($data) {
                return $query->where('is_active', (bool) $data['is_active']);
            })
            ->latest('id');
        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Category
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    public function findBy(string $column, mixed $value, array $data, array $relations = []):  LengthAwarePaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']):  LengthAwarePaginator|Collection
    {
        $query = $this->model::query()->active()->with($relations);
        return getCaseCollection($query, $data, $columns);
    }
    
    public function save(CategoryDto $dto): Category
    {
        $data = $dto->toArray();
        if ($dto->image) {
            $data['image'] = $this->uploadImage($dto->image, 'category');
        }

        return $this->model::create($data);
    }

    public function update(Category $category, CategoryDto $dto): Category
    {
        $data = $dto->toArray();
        if ($dto->image) {
            if ($category->image) $this->deleteImage($category->image, 'category');

            $data['image'] = $this->uploadImage($dto->image, 'category');
        }

        $category->update($data);
        return $category;
    }

    public function delete(Category $category): bool
    {
        if ($category->image) $this->deleteImage($category->image, 'category');
        return $category->delete();
    }

    public function activate(Category $category): Category
    {
        $category->update(['is_active' => !$category->is_active]);
        return $category;
    }
}
