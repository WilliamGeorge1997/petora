<?php

namespace Modules\Coupon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Branch\Entities\Branch;
use Spatie\Activitylog\Traits\LogsActivity;

class Coupon extends Model
{
    use HasFactory, LogsActivity;

    const FIXED = 1;
    const PERCENT = 2;

    protected $fillable = ['code', 'is_active', 'branch_id', 'num_of_uses', 'client_uses', 'counter', 'type', 'value', 'limit', 'date_from', 'date_to', 'time_from', 'time_to'];

    protected static $logName  = 'Coupon';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['updated_at'];
    protected static $logOnlyDirty = true;

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function discount($total)
    {
        $this->counter++;
        $this->save();
        if ($this->type == self::FIXED) {
            return $this->value;
        } else {
            $discount = (int) ($total * $this->value) / 100;
            if ($this->limit > 0 && $discount > $this->limit) return $this->limit;
            return $discount;
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    protected function scopeAvailable($query)
    {
        if (auth('admin')->check()) {
            $admin = auth('admin')->user();
            if ($admin->hasRole('Super Admin')) {
            } else if ($admin->hasRole('Branch Manager')) {
                $query->where('branch_id', $admin->branch_id);
            }
        }
    }
}
