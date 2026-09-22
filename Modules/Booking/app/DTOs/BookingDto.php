<?php

namespace Modules\Booking\DTOs;

use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Http\Requests\BookingRequest;
use Modules\Booking\Models\Booking;

readonly class BookingDto
{
    public function __construct(
        public int $clientId,
        public int $petId,
        public int $clinicId,
        public int $serviceId,
        public int $clinicServiceScheduleTimeId,
        public int $paymentMethodId,
        public string $bookingDate,
        public ?string $coupon = null,
        public ?string $notes = null,
    ) {}

    public static function fromRequest(BookingRequest $request): self
    {
        return new self(
            clientId: auth('client')->id(),
            petId: (int) $request->validated('pet_id'),
            clinicId: (int) $request->validated('clinic_id'),
            serviceId: (int) $request->validated('service_id'),
            clinicServiceScheduleTimeId: (int) $request->validated('clinic_service_schedule_time_id'),
            paymentMethodId: (int) $request->validated('payment_method_id'),
            bookingDate: $request->validated('booking_date'),
            coupon: $request->validated('coupon'),
            notes: $request->validated('notes'),
        );
    }

    public function toArray(): array
    {
        $data = [
            'client_id' => $this->clientId,
            'pet_id' => $this->petId,
            'clinic_id' => $this->clinicId,
            'service_id' => $this->serviceId,
            'clinic_service_schedule_time_id' => $this->clinicServiceScheduleTimeId,
            'payment_method_id' => $this->paymentMethodId,
            'booking_date' => $this->bookingDate,
            'notes' => $this->notes,
        ];

        $data['booking_no'] = generateSerial(Booking::class, 'BK');
        $data['booking_status_id'] = BookingStatus::Pending->value;

        return array_filter($data);
    }
}
