<?php

namespace Modules\Clinic\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Modules\Clinic\DTOs\ClinicServiceScheduleDto;
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

    public function findBy(string $column, mixed $value, array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->where($column, $value);
        return getCaseCollection($query, $data);
    }

    public function save(ClinicServiceScheduleDto $dto): ClinicServiceSchedule
    {
        return DB::transaction(function () use ($dto) {
            /** @var ClinicServiceSchedule $schedule */
            $schedule = $this->model::create($dto->toArray());

            if (!empty($dto->times)) {
                $schedule->times()->createMany($dto->times);
            }

            return $schedule;
        });
    }

    public function update(int|ClinicServiceSchedule $scheduleOrId, ClinicServiceScheduleDto $dto): ClinicServiceSchedule
    {
        $schedule = $this->resolveModel($scheduleOrId);

        return DB::transaction(function () use ($schedule, $dto) {
            $schedule->update($dto->toArray());

            $schedule->times()->delete();

            if (!empty($dto->times)) {
                $schedule->times()->createMany($dto->times);
            }

            return $schedule;
        });
    }

    public function delete(int|ClinicServiceSchedule $scheduleOrId): bool
    {
        $schedule = $this->resolveModel($scheduleOrId);
        return (bool) $schedule->delete();
    }
}
