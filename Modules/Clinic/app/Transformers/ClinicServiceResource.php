<?php

namespace Modules\Clinic\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClinicServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            $this->mergeWhen($this->relationLoaded('service'), fn() => $this->formatService()),
            'price'      => $this->price,
            'duration'   => $this->duration,
            'schedules'  => $this->whenLoaded('schedules', fn() => $this->formatSchedules()),
            'created_at' => $this->created_at->format('Y-m-d h:i A'),
            'updated_at' => $this->updated_at->format('Y-m-d h:i A'),
        ];
    }

    private function formatService(): array
    {
        return [
            'id'          => $this->service->id,
            'title'       => $this->service->title,
            'description' => $this->service->description,
            'image'       => $this->service->image,
        ];
    }

    private function formatSchedules()
    {
        $dayMap        = $this->additional['dayMap'] ?? [];
        $bookingCounts = $this->additional['bookingCounts'] ?? [];

        return $this->schedules->map(function ($schedule) use ($dayMap, $bookingCounts) {
            return $this->formatSchedule($schedule, $dayMap, $bookingCounts);
        });
    }

    private function formatSchedule($schedule, array $dayMap, array $bookingCounts): array
    {
        $date = $dayMap[$schedule->day] ?? null;

        return [
            'id'    => $schedule->id,
            'day'   => $schedule->day,
            'times' => $schedule->times->map(function ($time) use ($date, $bookingCounts) {
                return $this->formatTime($time, $date, $bookingCounts);
            }),
        ];
    }

    private function formatTime($time, ?string $date, array $bookingCounts): array
    {
        if (! $time->capacity) {
            return $this->slot($time, $date, true);
        }

        $available = ($bookingCounts[$time->id][$date] ?? 0) < $time->capacity;

        return $this->slot($time, $date, $available);
    }

    private function slot($time, string $date, bool $available): array
    {
        return [
            'id'           => $time->id,
            'from'         => $time->from,
            'to'           => $time->to,
            'date'         => $date,
            'is_available' => $available,
        ];
    }
}
