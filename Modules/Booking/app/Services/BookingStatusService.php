<?php

namespace Modules\Booking\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Modules\Booking\DTOs\BookingStatusDto;
use Modules\Booking\Models\BookingStatus;

class BookingStatusService
{
    private string $model = BookingStatus::class;

    public function findAll(array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): BookingStatus
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|BookingStatus $bookingStatusOrId): BookingStatus
    {
        return $bookingStatusOrId instanceof BookingStatus ? $bookingStatusOrId : $this->findById($bookingStatusOrId);
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

    public function save(BookingStatusDto $dto): BookingStatus
    {
        $data = $dto->toArray();

        return $this->model::create($data);
    }

    public function update(int|BookingStatus $bookingStatusOrId, BookingStatusDto $dto): BookingStatus
    {
        $bookingStatus = $this->resolveModel($bookingStatusOrId);
        $data = $dto->toArray();

        $bookingStatus->update($data);

        return $bookingStatus;
    }

    public function delete(int|BookingStatus $bookingStatusOrId): bool
    {
        $bookingStatus = $this->resolveModel($bookingStatusOrId);

        return $bookingStatus->delete();
    }

    public function activate(int|BookingStatus $bookingStatusOrId): BookingStatus
    {
        $bookingStatus = $this->resolveModel($bookingStatusOrId);
        $bookingStatus->update(['is_active' => ! $bookingStatus->is_active]);

        return $bookingStatus;
    }
}
