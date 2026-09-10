<?php

namespace Modules\Admin\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\CursorPaginator;
use Modules\Admin\DTOs\AdminDto;
use Modules\Admin\Models\Admin;
use Modules\Common\Helpers\UploaderHelper;

class AdminService
{
    use UploaderHelper;

    public function __construct(private Admin $model) {}

    public function findAll(array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model->query()->with($relations)->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Admin
    {
        return $this->model->with($relations)->findOrFail($id);
    }

    public function findBy(string $key, mixed $value, array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model->query()->with($relations)->where($key, $value);

        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model->query()->with($relations)->active();

        return getCaseCollection($query, $data);
    }

    public function save(AdminDto $dto): Admin
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $data['image'] = $this->uploadImage($data['image'], 'admin');
        }

        $admin = $this->model->create($data);

        if ($role) {
            $admin->assignRole($role);
        }

        return $this->findById($admin->id);
    }

    public function update(int $id, array $data): Admin
    {
        $admin = $this->findById($id);

        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $rawImage = $admin->getRawOriginal('image');
            if ($rawImage) {
                $this->deleteImage($rawImage, 'admin');
            }
            $data['image'] = $this->uploadImage($data['image'], 'admin', 70);
        }

        $role = $data['role'] ?? null;
        unset($data['role']);

        $admin->update($data);

        if ($role) {
            $admin->syncRoles([$role]);
        }

        return $this->findById($admin->id);
    }

    public function activate(int $id): void
    {
        $admin = $this->findById($id);
        $admin->is_active = ! $admin->is_active;
        $admin->save();
    }

    public function delete(int $id): void
    {
        $admin = $this->findById($id);
        $rawImage = $admin->getRawOriginal('image');
        if ($rawImage) {
            $this->deleteImage($rawImage, 'admin');
        }
        $admin->delete();
    }
}
