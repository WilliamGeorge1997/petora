<?php

namespace Modules\Clinic\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Modules\Clinic\DTOs\ClinicServiceScheduleDto;
use Modules\Service\Models\ClinicService;
use Modules\Service\Models\ClinicServiceSchedule;

class ClinicServiceScheduleService
{
    private string $model = ClinicServiceSchedule::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)
            ->filter($data)
            ->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): ClinicServiceSchedule
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|ClinicServiceSchedule $scheduleOrId): ClinicServiceSchedule
    {
        return $scheduleOrId instanceof ClinicServiceSchedule ? $scheduleOrId : $this->findById($scheduleOrId);
    }

    public function findBy(string $column, mixed $value, array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);

        return getCaseCollection($query, $data);
    }

    public function save(ClinicService|int $clinicServiceOrId, ClinicServiceScheduleDto $dto): ClinicServiceSchedule
    {
        $clinicServiceId = $clinicServiceOrId instanceof ClinicService ? $clinicServiceOrId->id : (int) $clinicServiceOrId;

        return DB::transaction(function () use ($clinicServiceId, $dto) {
            /** @var ClinicServiceSchedule $schedule */
            $schedule = $this->model::create([
                'clinic_service_id' => $clinicServiceId,
                'day' => $dto->day,
            ]);

            if (! empty($dto->times)) {
                $this->syncTimes($schedule, $dto->times);
            }

            return $schedule;
        });
    }

    public function update(int|ClinicServiceSchedule $scheduleOrId, ClinicServiceScheduleDto $dto): ClinicServiceSchedule
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

    public function syncTimes(ClinicServiceSchedule $schedule, array $times): void
    {
        $keptIds = [];

        foreach ($times as $item) {
            if (empty($item['from']) || empty($item['to'])) {
                continue;
            }

            $timeData = [
                'from' => $item['from'],
                'to' => $item['to'],
                'capacity' => ! empty($item['capacity']) ? (int) $item['capacity'] : null,
            ];

            if (! empty($item['id'])) {
                $schedule->times()->where('id', $item['id'])->update($timeData);
                $keptIds[] = (int) $item['id'];
            } else {
                $created = $schedule->times()->create($timeData);
                $keptIds[] = $created->id;
            }
        }

        $schedule->times()->whereNotIn('id', $keptIds)->delete();
    }

    public function delete(int|ClinicServiceSchedule $scheduleOrId): bool
    {
        $schedule = $this->resolveModel($scheduleOrId);

        return (bool) $schedule->delete();
    }
}
