<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Booking\DTOs\BookingDto;
use Modules\Booking\Enums\BookingStatus as BookingStatusEnum;
use Modules\Booking\Http\Requests\BookingRequest;
use Modules\Booking\Models\Booking;
use Modules\Booking\Services\BookingService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-booking|Create-booking|Edit-booking|Delete-booking', only: ['index', 'store'])]
#[Middleware('permission:Create-booking', only: ['create', 'store'])]
#[Middleware('permission:Edit-booking', only: ['edit', 'update'])]
#[Middleware('permission:Delete-booking', only: ['destroy'])]
class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $relations = ['clinicService.service', 'clinic', 'pet', 'client', 'bookingStatus'];
        $bookings = $this->bookingService->findAll($relations, $data);

        if ($request->ajax()) {
            return success(true, __('booking::message.booking.fetched'), $bookings->items());
        }

        $totalCount = Booking::count();
        $confirmedCount = Booking::where('booking_status_id', BookingStatusEnum::Confirmed->value)->count();
        $doneCount = Booking::where('booking_status_id', BookingStatusEnum::Completed->value)->count();
        $cancelCount = Booking::where('booking_status_id', BookingStatusEnum::Cancelled->value)->count();
        $viewModel = new \Modules\Booking\ViewModels\BookingViewModel;

        return view('booking::bookings.index', compact('bookings', 'totalCount', 'confirmedCount', 'doneCount', 'cancelCount', 'viewModel'));
    }

    public function create(): View
    {
        return view('booking::bookings.create');
    }

    public function store(BookingRequest $request): RedirectResponse
    {
        $dto = BookingDto::fromRequest($request);
        $this->bookingService->save($dto);

        return to_route('admin.booking.index')->with('success', __('booking::message.booking.created'));
    }

    public function show(int $booking_id): View
    {
        $relations = [
            'clinicService.service',
            'clinic',
            'pet',
            'client',
            'bookingStatus',
            'paymentMethod',
            'coupon',
            'histories.status',
            'histories.historible',
        ];
        $booking = $this->bookingService->findById($booking_id, $relations);

        return view('booking::bookings.show', compact('booking'));
    }

    public function edit(int $booking_id): View
    {
        $relations = ['clinicService.service', 'clinic', 'pet', 'client', 'bookingStatus'];
        $booking = $this->bookingService->findById($booking_id, $relations);
        $viewModel = new \Modules\Booking\ViewModels\BookingViewModel;

        return view('booking::bookings.edit', compact('booking', 'viewModel'));
    }

    public function update(BookingRequest $request, Booking $booking): RedirectResponse
    {
        $dto = BookingDto::fromRequest($request);
        $this->bookingService->update($booking, $dto);

        return to_route('admin.booking.index')->with('success', __('booking::message.booking.updated'));
    }

    public function destroy(Booking $booking): JsonResponse
    {
        $this->bookingService->delete($booking);

        return success(true, __('booking::message.booking.deleted'));
    }
}
