<?php

namespace Modules\Store\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Store\DTOs\StoreDeliveryScheduleDto;
use Modules\Store\Models\Store;
use Modules\Store\Models\StoreDeliverySchedule;
use Modules\Store\Models\StoreDeliveryScheduleTime;

class StoreDeliveryScheduleService
{
    private string $model = StoreDeliverySchedule::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): StoreDeliverySchedule
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|StoreDeliverySchedule $scheduleOrId): StoreDeliverySchedule
    {
        return $scheduleOrId instanceof StoreDeliverySchedule ? $scheduleOrId : $this->findById($scheduleOrId);
    }

    public function findBy(string $column, mixed $value, array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value)->latest('id');
        return getCaseCollection($query, $data);
    }

    public function save(Store|int $storeOrId, StoreDeliveryScheduleDto $dto): StoreDeliverySchedule
    {
        $storeId = $storeOrId instanceof Store ? $storeOrId->id : (int) $storeOrId;

        return DB::transaction(function () use ($storeId, $dto) {
            /** @var StoreDeliverySchedule $schedule */
            $schedule = $this->model::create([
                'store_id' => $storeId,
                'day' => $dto->day,
            ]);

            if (! empty($dto->times)) {
                $this->syncTimes($schedule, $dto->times);
            }

            return $schedule;
        });
    }

    public function update(int|StoreDeliverySchedule $scheduleOrId, StoreDeliveryScheduleDto $dto): StoreDeliverySchedule
    {
        $schedule = $this->resolveModel($scheduleOrId);

        return DB::transaction(function () use ($schedule, $dto) {
            $schedule->update($dto->toArray());

            if (! empty($dto->times)) {
                $this->syncTimes($schedule, $dto->times);
            }

            return $schedule;
        });
    }

    public function syncTimes(StoreDeliverySchedule $schedule, array $times): void
    {
        $keptIds = [];

        foreach ($times as $item) {
            if (empty($item['from']) || empty($item['to'])) {
                continue;
            }

            if (! empty($item['id'])) {
                $schedule->times()->where('id', $item['id'])->update([
                    'from' => $item['from'],
                    'to' => $item['to'],
                ]);
                $keptIds[] = (int) $item['id'];
            } else {
                $created = $schedule->times()->create([
                    'from' => $item['from'],
                    'to' => $item['to'],
                ]);
                $keptIds[] = $created->id;
            }
        }

        $schedule->times()->whereNotIn('id', $keptIds)->delete();
    }

    public function getTimes(int $scheduleTimeId, int $storeId): array
    {
        $scheduleTime = StoreDeliveryScheduleTime::findOrFail($scheduleTimeId);

        if ($scheduleTime->schedule->store_id !== $storeId) {
            throw ValidationException::withMessages([
                'store_delivery_schedule_time_id' => __('order::message.store_delivery_schedule_time_id_invalid'),
            ]);
        }

        return ['delivery_time_from' => $scheduleTime->from, 'delivery_time_to' => $scheduleTime->to];
    }

    public function delete(int|StoreDeliverySchedule $scheduleOrId): bool
    {
        $schedule = $this->resolveModel($scheduleOrId);

        return (bool) $schedule->delete();
    }
}
