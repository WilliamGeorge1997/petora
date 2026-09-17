<?php

namespace Modules\Notification\Services;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Notification\DTOs\NotificationDto;
use Modules\Notification\Models\Notification;

class NotificationService
{
    use UploaderHelper;

    private string $model = Notification::class;

    public function findAll(array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Notification
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    public function findBy(string $column, mixed $value, array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);

        return getCaseCollection($query, $data);
    }

    public function notificationsInAdminPanel(): Collection
    {
        return $this->model::query()
            ->groupBy('group_by')
            ->whereNull('subject_id')
            ->select(
                'id',
                'group_by',
                'created_at',
                'title',
                DB::raw('count(*) as total'),
                DB::raw('count(DISTINCT(read_at)) as readCount')
            )
            ->get();
    }

    public function save(NotificationDto $dto): Notification
    {
        $data = $dto->toArray();

        if ($dto->image) {
            $data['image'] = $this->uploadImage($dto->image, 'notification');
        } elseif ($dto->imageName) {
            $data['image'] = $dto->imageName;
        }

        return $this->model::create($data);
    }

    public function delete(int $id): void
    {
        $notification = $this->findById($id);

        if ($notification->image) {
            $this->deleteImage($notification->getRawOriginal('image') ?? '', 'notification');
        }

        $notification->delete();
    }
}
