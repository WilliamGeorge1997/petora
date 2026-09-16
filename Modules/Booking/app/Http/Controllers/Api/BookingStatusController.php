<?php

namespace Modules\Booking\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Booking\Services\BookingStatusService;

class BookingStatusController extends Controller
{
    public function __construct(private BookingStatusService $bookingStatusService) {}

    public function index(Request $request): JsonResponse
    {
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $bookingStatuses = $this->bookingStatusService->active($data);

        return success(true, __('booking::message.status.fetched'), $bookingStatuses);
    }
}
