<?php

namespace Modules\Booking\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Client\Models\Client;
use Modules\Clinic\Models\Clinic;
use Modules\Coupon\Models\Coupon;
use Modules\Order\Models\PaymentMethod;
use Modules\Pet\Models\Pet;
use Modules\Service\Models\ClinicService;
use Modules\Service\Models\ClinicServiceScheduleTime;
use Modules\Service\Models\Service;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_no',
        'subtotal',
        'discount',
        'discount_type',
        'tax',
        'total',
        'booking_date',
        'client_id',
        'pet_id',
        'clinic_id',
        'service_id',
        'clinic_service_id',
        'clinic_service_schedule_time_id',
        'coupon_id',
        'payment_method_id',
        'booking_status_id',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'discount_type' => 'integer',
        'booking_date' => 'datetime:Y-m-d',
    ];

    protected $hidden = [
        'client_id',
        'pet_id',
        'clinic_id',
        'service_id',
        'clinic_service_id',
        'clinic_service_schedule_time_id',
        'coupon_id',
        'payment_method_id',
        'booking_status_id',
    ];

    // Date serialization
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d h:i A');
    }

    // Scopes
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['booking_no'] ?? null, function ($query, $bookingNo) {
                $query->where('booking_no', 'LIKE', "%{$bookingNo}%");
            })
            ->when($filters['client_id'] ?? null, function ($query, $clientId) {
                $query->where('client_id', $clientId);
            })
            ->when($filters['pet_id'] ?? null, function ($query, $petId) {
                $query->where('pet_id', $petId);
            })
            ->when($filters['clinic_id'] ?? null, function ($query, $clinicId) {
                $query->where('clinic_id', $clinicId);
            })
            ->when($filters['clinic_service_id'] ?? null, function ($query, $serviceId) {
                $query->where('clinic_service_id', $serviceId);
            })
            ->when($filters['booking_status_id'] ?? null, function ($query, $statusId) {
                $query->where('booking_status_id', $statusId);
            })
            ->when($filters['booking_date_from'] ?? null, function ($query, $dateFrom) {
                $query->whereDate('booking_date', '>=', $dateFrom);
            })
            ->when($filters['booking_date_to'] ?? null, function ($query, $dateTo) {
                $query->whereDate('booking_date', '<=', $dateTo);
            })
            ->when($filters['created_at_from'] ?? null, function ($query, $dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($filters['created_at_to'] ?? null, function ($query, $dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            });
    }

    // Relations
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function clinicService(): BelongsTo
    {
        return $this->belongsTo(ClinicService::class);
    }

    public function clinicServiceScheduleTime(): BelongsTo
    {
        return $this->belongsTo(ClinicServiceScheduleTime::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function bookingStatus(): BelongsTo
    {
        return $this->belongsTo(BookingStatus::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(BookingHistory::class);
    }
}
