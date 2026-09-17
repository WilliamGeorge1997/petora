<?php

namespace Modules\Driver\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Modules\Common\Helpers\UploaderHelper;
use Modules\Driver\DTOs\DriverDto;
use Modules\Driver\Models\Driver;

class DriverService
{
    use UploaderHelper;

    private string $model = Driver::class;

    public function findAll(array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): Driver
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Driver $driverOrId): Driver
    {
        return $driverOrId instanceof Driver ? $driverOrId : $this->findById($driverOrId);
    }

    public function findBy(string $column, mixed $value, array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);

        return getCaseCollection($query, $data);
    }

    public function active(array $data = [], array $relations = [], array $columns = ['*']): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->active()->with($relations);

        return getCaseCollection($query, $data, $columns);
    }

    public function available(array $data = [], array $relations = [], array $columns = ['*']): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->available()->with($relations);

        return getCaseCollection($query, $data, $columns);
    }

    public function save(DriverDto $dto): Driver
    {
        return DB::transaction(function () use ($dto) {
            $data = $dto->toArray();
            if ($dto->image) {
                $data['image'] = $this->uploadImage($dto->image, 'driver');
            }

            /** @var Driver $driver */
            $driver = $this->model::create($data);

            return $driver;
        });
    }

    public function update(int|Driver $driverOrId, DriverDto $dto): Driver
    {
        $driver = $this->resolveModel($driverOrId);

        return DB::transaction(function () use ($driver, $dto) {
            $data = $dto->toArray();
            if ($dto->image) {
                $rawImage = $driver->getRawOriginal('image');
                if ($rawImage) {
                    $this->deleteImage($rawImage, 'driver');
                }

                $data['image'] = $this->uploadImage($dto->image, 'driver');
            }

            $driver->update($data);

            return $driver;
        });
    }

    public function delete(int|Driver $driverOrId): bool
    {
        $driver = $this->resolveModel($driverOrId);
        $rawImage = $driver->getRawOriginal('image');
        if ($rawImage) {
            $this->deleteImage($rawImage, 'driver');
        }

        return $driver->delete();
    }

    public function activate(int|Driver $driverOrId): Driver
    {
        $driver = $this->resolveModel($driverOrId);
        $driver->update(['is_active' => ! $driver->is_active]);

        return $driver;
    }

    public function availability(int|Driver $driverOrId): Driver
    {
        $driver = $this->resolveModel($driverOrId);
        $driver->update(['is_available' => ! $driver->is_available]);

        return $driver;
    }

    public function findToken(int|Driver $driverOrId): ?string
    {
        $driver = $this->resolveModel($driverOrId);

        return $driver->fcm_token;
    }

    public function changeLocale(int|Driver $driverOrId): Driver
    {
        $driver = $this->resolveModel($driverOrId);
        $locale = $driver->locale === 'en' ? 'ar' : 'en';
        $driver->update(['locale' => $locale]);

        return $driver;
    }
}
