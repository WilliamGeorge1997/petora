<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Admin\Enums\AdminRole;
use Modules\Admin\Models\Admin;
use Modules\Client\Models\Address;
use Modules\Client\Models\Client;
use Modules\Clinic\Models\Clinic;
use Modules\Clinic\Models\ClinicDeliveryScheduleTime;
use Modules\Coupon\Models\Coupon;
use Modules\Driver\Models\Driver;
use Modules\Store\Models\Store;
use Modules\Store\Models\StoreDeliveryScheduleTime;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'subtotal',
        'discount',
        'discount_type',
        'tax',
        'delivery_fee',
        'total',
        'quantity',
        'delivery_date',
        'delivery_time_from',
        'delivery_time_to',
        'client_id',
        'store_id',
        'store_delivery_schedule_time_id',
        'clinic_id',
        'clinic_delivery_schedule_time_id',
        'address_id',
        'coupon_id',
        'driver_id',
        'order_method_id',
        'payment_method_id',
        'order_status_id',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'discount_type' => 'integer',
        'quantity' => 'integer',
        'delivery_date' => 'datetime:Y-m-d',
    ];

    protected $hidden = [
        'client_id',
        'store_id',
        'store_delivery_schedule_time_id',
        'clinic_id',
        'clinic_delivery_schedule_time_id',
        'address_id',
        'coupon_id',
        'driver_id',
        'order_method_id',
        'payment_method_id',
        'order_status_id',
    ];

    // Date serialization
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    // Scopes
    public function scopeFilter(Builder $query, array $filters = []): Builder
    {
        return $query
            ->when($filters['order_no'] ?? null, function (Builder $query, $orderNo) {
                $query->where('order_no', $orderNo);
            })
            ->when($filters['client_id'] ?? null, function (Builder $query, $clientId) {
                $query->where('client_id', $clientId);
            })
            ->when($filters['store_id'] ?? null, function (Builder $query, $storeId) {
                $query->where('store_id', $storeId);
            })
            ->when($filters['clinic_id'] ?? null, function (Builder $query, $clinicId) {
                $query->where('clinic_id', $clinicId);
            })
            ->when($filters['driver_id'] ?? null, function (Builder $query, $driverId) {
                $query->where('driver_id', $driverId);
            })
            ->when($filters['order_status_id'] ?? null, function (Builder $query, $statusId) {
                $query->where('order_status_id', $statusId);
            })
            ->when($filters['order_status_ids'] ?? null, function (Builder $query, $statusIds) {
                $query->whereIn('order_status_id', (array) $statusIds);
            })
            ->when($filters['delivery_date_from'] ?? null, function (Builder $query, $dateFrom) {
                $query->whereDate('delivery_date', '>=', $dateFrom);
            })
            ->when($filters['delivery_date_to'] ?? null, function (Builder $query, $dateTo) {
                $query->whereDate('delivery_date', '<=', $dateTo);
            })
            ->when($filters['from'] ?? null, function (Builder $query, $from) {
                $query->whereDate('created_at', '>=', $from);
            })
            ->when($filters['to'] ?? null, function (Builder $query, $to) {
                $query->whereDate('created_at', '<=', $to);
            });
    }

    public function scopeAvailable(Builder $query): Builder
    {
        /** @var Admin|null $admin */
        $admin = auth('admin')->user();

        if ($admin) {
            if ($admin->hasRole(AdminRole::SuperAdmin->value)) {
                return $query;
            }

            if ($admin->hasRole(AdminRole::CompanyManager->value) && $admin->company_id) {
                return $query->whereRelation('store', 'company_id', $admin->company_id);
            }

            if ($admin->hasRole(AdminRole::StoreManager->value) && $admin->store_id) {
                return $query->where('store_id', $admin->store_id);
            }

            if ($admin->hasRole(AdminRole::ClinicManager->value) && $admin->clinic_id) {
                return $query->where('clinic_id', $admin->clinic_id);
            }

            return $query->whereRaw('1 = 0');
        }

        return $query;
    }

    // Relations
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function storeDeliveryScheduleTime(): BelongsTo
    {
        return $this->belongsTo(StoreDeliveryScheduleTime::class);
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function clinicDeliveryScheduleTime(): BelongsTo
    {
        return $this->belongsTo(ClinicDeliveryScheduleTime::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function orderMethod(): BelongsTo
    {
        return $this->belongsTo(OrderMethod::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function orderStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(OrderHistory::class);
    }
}
