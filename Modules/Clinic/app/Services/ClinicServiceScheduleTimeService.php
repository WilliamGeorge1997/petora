<?php

namespace Modules\Clinic\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Validation\ValidationException;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\Booking;
use Modules\Service\Models\ClinicService;
use Modules\Service\Models\ClinicServiceScheduleTime;

class ClinicServiceScheduleTimeService
{
    private string $model = ClinicServiceScheduleTime::class;

    public function findAll(array $data, array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations)->latest('id');

        return getCaseCollection($query, $data);
    }

    public function findById(int $id, array $relations = []): ClinicServiceScheduleTime
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|ClinicServiceScheduleTime $timeOrId): ClinicServiceScheduleTime
    {
        return $timeOrId instanceof ClinicServiceScheduleTime ? $timeOrId : $this->findById($timeOrId);
    }

    public function findBy(string|array $column, mixed $value = null, array $data = [], array $relations = []): LengthAwarePaginator|CursorPaginator|Collection
    {
        $query = $this->model::query()->with($relations);

        if (is_array($column)) {
            $query->where($column);
        } else {
            $query->where($column, $value);
        }

        return getCaseCollection($query, $data);
    }

    public function checkSlot(int $slotId, int $clinicServiceId, string $bookingDate): ClinicServiceScheduleTime
    {
        $slot = $this->findById($slotId, ['schedule']);

        if ($slot->schedule->clinic_service_id !== $clinicServiceId) {
            throw ValidationException::withMessages([
                'clinic_service_schedule_time_id' => __('booking::message.clinic_service_schedule_time_not_found')
            ]);
        }

        if ($slot->capacity && $slot->capacity > 0) {
            $activeBookingsCount = Booking::query()
                ->where('clinic_service_schedule_time_id', $slotId)
                ->where('booking_date', $bookingDate)
                ->whereNotIn('booking_status_id', [BookingStatus::Cancelled->value])
                ->count();

            if ($activeBookingsCount >= $slot->capacity) {
                throw ValidationException::withMessages([
                    'clinic_service_schedule_time_id' => __('booking::message.slot_capacity_exceeded')
                ]);
            }
        }

        return $slot;
    }

    public function availability(ClinicService $clinicService): array
    {
        $dates = $this->dates();

        return [
            'dayMap'        => $this->dayMap($dates),
            'bookingCounts' => $this->bookingCounts($this->slotIds($clinicService), $dates),
        ];
    }

    //Helpers============================================
    private function dates(): array
    {
        return collect(range(0, 6))
            ->map(function ($offset) {
                return now()->addDays($offset)->format('Y-m-d');
            })
            ->all();
    }

    private function dayMap(array $dates): array
    {
        return collect($dates)
            ->keyBy(function ($date) {
                return strtolower(date('l', strtotime($date)));
            })
            ->all();
    }

    private function slotIds(ClinicService $clinicService): array
    {
        return $clinicService->schedules
            ->flatMap(function ($schedule) {
                return $schedule->times->where('capacity', '>', 0)->pluck('id');
            })
            ->all();
    }

    private function bookingCounts(array $slotIds, array $dates): array
    {
        if (empty($slotIds)) {
            return [];
        }

        return Booking::query()
            ->selectRaw('clinic_service_schedule_time_id, booking_date, COUNT(*) as count')
            ->whereIn('clinic_service_schedule_time_id', $slotIds)
            ->whereBetween('booking_date', [$dates[0], end($dates)])
            ->where('booking_status_id', '!=', BookingStatus::Cancelled->value)
            ->groupBy('clinic_service_schedule_time_id', 'booking_date')
            ->get()
            ->groupBy('clinic_service_schedule_time_id')
            ->map(function ($rows) {
                return $rows->mapWithKeys(function ($row) {
                    $date = $row->booking_date instanceof \Carbon\Carbon
                        ? $row->booking_date->format('Y-m-d')
                        : substr((string)$row->booking_date, 0, 10);

                    return [$date => $row->count];
                })->all();
            })
            ->all();
    }
}
