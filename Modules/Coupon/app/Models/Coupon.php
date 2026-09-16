<?php

namespace Modules\Coupon\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Coupon\Enums\CouponType;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use \Modules\Coupon\Enums\CouponDiscountOn;

class Coupon extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'code',
        'is_active',
        'num_of_uses',
        'counter',
        'type',
        'value',
        'limit',
        'date_from',
        'date_to',
        'time_from',
        'time_to',
        'client_uses',
        'discount_on',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'date_from' => 'date',
        'date_to' => 'date',
        'type' => CouponType::class,
        'discount_on' => CouponDiscountOn::class,
    ];

    // Activity log options
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Coupon')
            ->logAll()
            ->logOnlyDirty()
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->dontLogEmptyChanges();
    }

    // Date serialization
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function discount(float $total)
    {
        $this->counter++;
        $this->save();
        
        if ($this->type === CouponType::Fixed) {
            return min($this->value, $total);
        } else {
            $discount = (int) ($total * $this->value) / 100;
            if ($this->limit > 0 && $discount > $this->limit) {
                return $this->limit;
            }
            return $discount;
        }
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['code'] ?? null, function ($query, $code) {
                $query->where('code', 'like', '%' . $code . '%');
            })
            ->when(isset($filters['is_active']) && $filters['is_active'] !== '', function ($query) use ($filters) {
                $query->where('is_active', (bool) $filters['is_active']);
            })
            ->when($filters['type'] ?? null, function ($query, $type) {
                $query->where('type', $type);
            });
    }
}
