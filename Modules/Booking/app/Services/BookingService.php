<?php

namespace Modules\Booking\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Booking\DTOs\BookingDto;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\Booking;
use Modules\Client\Models\Client;
use Modules\Clinic\Services\ClinicServiceScheduleTimeService;
use Modules\Clinic\Services\ClinicServiceService;
use Modules\Coupon\Models\Coupon;
use Modules\Coupon\Services\CouponService;
use Modules\Order\Enums\DiscountType;
use Modules\Service\Models\ClinicService;

class BookingService
{
    private $model = Booking::class;

    public function findAll(array $relations = [], array $data = []): LengthAwarePaginator|CursorPaginator|EloquentCollection
    {
        $bookings = $this->model::query()
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

    public function findById(int $id, array $relations = [])
    {
        return $this->model::with($relations)->findOrFail($id);
    }

    protected function resolveModel(int|Booking $bookingOrId): Booking
    {
        return $bookingOrId instanceof Booking ? $bookingOrId : $this->findById($bookingOrId);
    }

    public function findBy($key, $value, array $relations = [], $paginate = null)
    {
        if ($paginate ?? null) {
            return $this->model::latest()->with($relations)->where($key, $value)->paginate($paginate);
        }
        return $this->model::latest()->with($relations)->where($key, $value)->get();
    }

    public function save(BookingDto $dto): Booking
    {
        $data = $dto->toArray();
        $clinicService = app(ClinicServiceService::class)->findBy([
            'clinic_id' => $dto->clinicId,
            'service_id' => $dto->serviceId,
        ])->first();

        if (!$clinicService) {
            throw ValidationException::withMessages([
                'service_id' => __('booking::message.clinic_service_not_found')
            ]);
        }
        
        $coupon = app(CouponService::class)->checkCoupon($dto->coupon, $dto->clientId);
        if (!empty($coupon)) $data['coupon_id'] = $coupon->id;

        $data = array_merge(
            $data,
            $this->prepareBookingDetails($dto, $clinicService),
            $this->calcBookingDetails((float) $clinicService->price, $coupon)
        );

        $booking = DB::transaction(function () use ($data) {
            $booking = $this->model::create($data);
            app(BookingHistoryService::class)->save($booking, $data['booking_status_id'], $data['client_id'], Client::class, @$data['notes']);
            return $booking;
        });

        return $booking;
    }

    public function update(int|Booking $bookingOrId, BookingDto $dto)
    {
        $data = $dto->toArray();
        $booking = $this->resolveModel($bookingOrId);
        $booking->update($data);
        return $booking;
    }

    public function delete(int|Booking $bookingOrId)
    {
        $booking = $this->resolveModel($bookingOrId);
        $booking->delete();
    }

    //Helpers=======================================
    private function prepareBookingDetails(BookingDto $dto, ClinicService $clinicService): array
    {
        app(ClinicServiceScheduleTimeService::class)->checkSlot(
            $dto->clinicServiceScheduleTimeId,
            $clinicService->id,
            $dto->bookingDate
        );

        return [
            'clinic_service_id' => $clinicService->id,
        ];
    }

    private function calcBookingDetails(float $base_total, ?Coupon $coupon): array
    {
        $tax = $this->calcTax($base_total);
        $discountData = $this->calcDiscount($base_total, $coupon);

        $total = $base_total + $tax - $discountData['discount'];

        return [
            'subtotal'      => $base_total,
            'tax'           => $tax,
            'discount'      => $discountData['discount'],
            'discount_type' => $discountData['discount_type'],
            'total'         => $total,
        ];
    }

    private function calcTax(float $base_total): float
    {
        $base_tax = (float) getSetting('tax'); //Percent
        return ($base_total * $base_tax) / 100;
    }

    private function calcDiscount(float $base_total, ?Coupon $coupon = null): array
    {
        $discount = 0;
        $discount_type = null;

        if ($coupon) {
            $discount_type = DiscountType::Coupon->value;
            $discount = $coupon->discount($base_total);
        }

        return [
            'discount'      => $discount,
            'discount_type' => $discount_type,
        ];
    }
}
