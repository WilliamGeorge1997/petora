<?php

namespace Modules\Booking\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Booking\DTOs\BookingStatusDto;
use Modules\Booking\Http\Requests\BookingStatusRequest;
use Modules\Booking\Models\BookingStatus;
use Modules\Booking\Services\BookingStatusService;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-bookingstatus|Create-bookingstatus|Edit-bookingstatus|Delete-bookingstatus', only: ['index', 'store'])]
#[Middleware('permission:Create-bookingstatus', only: ['create', 'store'])]
#[Middleware('permission:Edit-bookingstatus', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-bookingstatus', only: ['destroy'])]
class BookingStatusController extends Controller
{
    public function __construct(private BookingStatusService $bookingStatusService) {}

    public function index(Request $request): View|JsonResponse
    {
        $data = $request->merge(['paginated' => 50])->all();
        $bookingStatuses = $this->bookingStatusService->findAll($data);
        if ($request->ajax()) {
            return success(true, __('booking::message.status.fetched'), $bookingStatuses->items());
        }

        return view('booking::booking_statuses.index', compact('bookingStatuses'));
    }

    public function create(): View
    {
        return view('booking::booking_statuses.create');
    }

    public function store(BookingStatusRequest $request)
    {
        $dto = BookingStatusDto::fromRequest($request);
        $this->bookingStatusService->save($dto);

        return to_route('admin.booking_status.index')->with('success', __('booking::message.status.created'));
    }

    public function edit(BookingStatus $bookingStatus): View
    {
        return view('booking::booking_statuses.edit', compact('bookingStatus'));
    }

    public function update(BookingStatusRequest $request, BookingStatus $bookingStatus)
    {
        $dto = BookingStatusDto::fromRequest($request);
        $this->bookingStatusService->update($bookingStatus, $dto);

        return to_route('admin.booking_status.index')->with('success', __('booking::message.status.updated'));
    }

    public function destroy(BookingStatus $bookingStatus): JsonResponse
    {
        $this->bookingStatusService->delete($bookingStatus);

        return success(true, __('booking::message.status.deleted'));
    }

    public function activate(BookingStatus $bookingStatus): JsonResponse
    {
        $bookingStatus = $this->bookingStatusService->activate($bookingStatus);

        return success(
            true,
            $bookingStatus->is_active ? __('booking::message.status.activated') : __('booking::message.status.deactivated'),
            $bookingStatus
        );
    }
}
