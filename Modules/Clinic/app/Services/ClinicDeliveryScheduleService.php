<?php

namespace Modules\Clinic\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Modules\Clinic\DTOs\ClinicDeliveryScheduleDto;
use Modules\Clinic\Models\Clinic;
use Modules\Clinic\Models\ClinicDeliverySchedule;

class ClinicDeliveryScheduleService
{
    private string $model = ClinicDeliverySchedule::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): ClinicDeliverySchedule
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|ClinicDeliverySchedule $scheduleOrId): ClinicDeliverySchedule
    {
        return $scheduleOrId instanceof ClinicDeliverySchedule ? $scheduleOrId : $this->findById($scheduleOrId);
    }

    public function findBy(string $column, mixed $value, array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);

        return getCaseCollection($query, $data);
    }

    public function getSchedulesForClinic(Clinic|int $clinicOrId): Collection
    {
        $clinicId = $clinicOrId instanceof Clinic ? $clinicOrId->id : (int) $clinicOrId;

        return $this->model::query()->where('clinic_id', $clinicId)->with('times')->get();
    }

    public function save(Clinic|int $clinicOrId, ClinicDeliveryScheduleDto $dto): ClinicDeliverySchedule
    {
        $clinicId = $clinicOrId instanceof Clinic ? $clinicOrId->id : (int) $clinicOrId;

        return DB::transaction(function () use ($clinicId, $dto) {
            /** @var ClinicDeliverySchedule $schedule */
            $schedule = $this->model::create([
                'clinic_id' => $clinicId,
                'day' => $dto->day,
            ]);

            if (! empty($dto->times)) {
                $this->syncTimes($schedule, $dto->times);
            }

            return $schedule;
        });
    }

    public function update(int|ClinicDeliverySchedule $scheduleOrId, ClinicDeliveryScheduleDto $dto): ClinicDeliverySchedule
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

    public function syncTimes(ClinicDeliverySchedule $schedule, array $times): void
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

    public function delete(int|ClinicDeliverySchedule $scheduleOrId): bool
    {
        $schedule = $this->resolveModel($scheduleOrId);

        return (bool) $schedule->delete();
    }
}
