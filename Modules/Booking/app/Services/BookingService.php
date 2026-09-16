<?php

namespace Modules\Booking\Services;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Modules\Booking\DTOs\BookingDto;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\Booking;
use Modules\Client\Models\Client;
use Modules\Coupon\Models\Coupon;
use Modules\Coupon\Services\CouponService;
use Modules\Order\Enums\DiscountType;
use Modules\Service\Models\ClinicService;
use Modules\Service\Models\ClinicServiceScheduleTime;

class BookingService
{
    public function __construct(private CouponService $couponService) {}

    public function findAll(array $relations = [], array $data = []): LengthAwarePaginator|CursorPaginator|EloquentCollection
    {
        $bookings = Booking::query()
            ->when($data['clinic_id'] ?? null, function ($q) use ($data) {
                $q->where('clinic_id', $data['clinic_id']);
            })
            ->when($data['client_id'] ?? null, function ($q) use ($data) {
                $q->where('client_id', $data['client_id']);
            })
            ->when($data['pet_id'] ?? null, function ($q) use ($data) {
                $q->where('pet_id', $data['pet_id']);
            })
            ->when($data['booking_status_id'] ?? null, function ($q) use ($data) {
                $q->where('booking_status_id', $data['booking_status_id']);
            })
            ->when($data['booking_no'] ?? null, function ($q) use ($data) {
                $q->where('booking_no', $data['booking_no']);
            })
            ->when($data['booking_date_from'] ?? null, function ($q) use ($data) {
                $q->whereDate('booking_date', '>=', $data['booking_date_from']);
            })
            ->when($data['booking_date_to'] ?? null, function ($q) use ($data) {
                $q->whereDate('booking_date', '<=', $data['booking_date_to']);
            })
            ->with($relations)
            ->orderByDesc('id');

        return getCaseCollection($bookings, $data);
    }

    public function findById(int $id, array $relations = []): Booking
    {
        return Booking::with($relations)->findOrFail($id);
    }

    public function findBy(string $key, mixed $value, array $relations = [], ?int $paginate = null)
    {
        $query = Booking::query()->latest()->with($relations)->where($key, $value);

        if ($paginate) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }

    public function save(BookingDto $dto): Booking
    {
        $data = $dto->toArray();

        // 1. Resolve clinic_id and price from ClinicService
        $clinicService = ClinicService::findOrFail($dto->clinicServiceId);
        $data['clinic_id'] = $clinicService->clinic_id;

        // 2. Check time slot capacity
        $this->checkSlotCapacity($dto->clinicServiceScheduleTimeId, $dto->bookingDate);

        // 3. Check Coupon
        $coupon = null;
        if (! empty($dto->coupon)) {
            $coupon = $this->couponService->checkCoupon($dto->coupon, $dto->clientId);
            if (! empty($coupon)) {
                $data['coupon_id'] = $coupon->id;
            }
        }

        // 4. Calculate Financials (subtotal, tax, discount, total)
        $financials = $this->calcBookingTotals((float) $clinicService->price, $coupon);
        $data = array_merge($data, $financials);

        // 5. DB Transaction
        return DB::transaction(function () use ($data) {
            $booking = Booking::create($data);

            app(BookingHistoryService::class)->save(
                $booking,
                $data['booking_status_id'],
                $data['client_id'],
                Client::class,
                $data['notes'] ?? null
            );

            return $booking;
        });
    }

    public function update(int|Booking $bookingOrId, array|BookingDto $data): Booking
    {
        $booking = $bookingOrId instanceof Booking ? $bookingOrId : $this->findById($bookingOrId);
        $updateData = $data instanceof BookingDto ? $data->toArray() : $data;

        $booking->update($updateData);

        return $booking;
    }

    public function delete(int|Booking $bookingOrId): bool
    {
        $booking = $bookingOrId instanceof Booking ? $bookingOrId : $this->findById($bookingOrId);

        return $booking->delete();
    }

    // Helpers
    private function checkSlotCapacity(int $slotId, string $bookingDate): void
    {
        $slot = ClinicServiceScheduleTime::findOrFail($slotId);

        if ($slot->capacity && $slot->capacity > 0) {
            $activeBookingsCount = Booking::query()
                ->where('clinic_service_schedule_time_id', $slotId)
                ->where('booking_date', $bookingDate)
                ->whereNotIn('booking_status_id', [BookingStatus::Cancelled->value])
                ->count();

            if ($activeBookingsCount >= $slot->capacity) {
                throw new Exception(__('booking::message.slot_capacity_exceeded'));
            }
        }
    }

    private function calcBookingTotals(float $subtotal, ?Coupon $coupon): array
    {
        $tax = $this->calcTax($subtotal);
        $discountData = $this->calcDiscount($subtotal, $coupon);
        $total = max(0, $subtotal + $tax - $discountData['discount']);

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discountData['discount'],
            'discount_type' => $discountData['discount_type'],
            'total' => $total,
        ];
    }

    private function calcTax(float $baseTotal): float
    {
        $baseTax = (float) (getSetting('tax') ?? 0);

        return ($baseTotal * $baseTax) / 100;
    }

    private function calcDiscount(float $baseTotal, ?Coupon $coupon = null): array
    {
        $discount = 0;
        $discountType = null;

        if ($coupon) {
            $discountType = DiscountType::Coupon->value;
            $discount = $coupon->discount($baseTotal);
        }

        return [
            'discount' => $discount,
            'discount_type' => $discountType,
        ];
    }
}
