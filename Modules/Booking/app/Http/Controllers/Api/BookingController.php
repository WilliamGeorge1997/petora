<?php

namespace Modules\Booking\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Booking\DTOs\BookingDto;
use Modules\Booking\Http\Requests\BookingRequest;
use Modules\Booking\Models\Booking;
use Modules\Booking\Services\BookingService;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    public function index(Request $request): JsonResponse
    {
        $clientId = auth('client')->id();
        $data = $request->merge([
            'client_id' => $clientId,
            'pagination_type' => 'cursor',
        ])->all();

        $relations = ['clinicService.service', 'clinic', 'pet', 'bookingStatus'];
        $bookings = $this->bookingService->findAll($relations, $data);

        return success(true, __('booking::message.booking.fetched'), $bookings);
    }

    public function store(BookingRequest $request): JsonResponse
    {
        $dto = BookingDto::fromRequest($request);
        $booking = $this->bookingService->save($dto);

        return success(true, __('booking::message.booking.created'), [
            'booking_no' => $booking->booking_no,
            'id' => $booking->id,
        ]);
    }

    public function show(int $booking_id): JsonResponse
    {
        $relations = [
            'clinicService.service',
            'clinic',
            'pet',
            'bookingStatus',
            'paymentMethod',
            'coupon',
            'histories.status',
        ];

        $booking = $this->bookingService->findById($booking_id, $relations);

        if (auth('client')->check() && $booking->client_id !== auth('client')->id()) {
            abort(403, __('booking::message.booking.unauthorized'));
        }

        return success(true, __('booking::message.booking.fetched'), $booking);
    }

    public function destroy(Booking $booking): JsonResponse
    {
        if (auth('client')->check() && $booking->client_id !== auth('client')->id()) {
            abort(403, __('booking::message.booking.unauthorized'));
        }

        $this->bookingService->delete($booking);

        return success(true, __('booking::message.booking.deleted'));
    }
}
