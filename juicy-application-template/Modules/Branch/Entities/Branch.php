<?php

namespace Modules\Branch\Entities;

use Modules\Admin\Entities\Admin;
use Modules\Order\Entities\Order;
use Modules\Branch\Entities\ShortUrl;
use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Entities\OrderMethod;
use Modules\Branch\Entities\WorkingHour;
use Spatie\Translatable\HasTranslations;
use Modules\Order\Entities\PaymentMethod;
use Modules\Branch\Entities\BranchSetting;
use Modules\Branch\Entities\BranchQrSetting;
use Modules\Branch\Entities\DeliveryArea;
use Modules\Branch\Entities\DeliveryCharge;
use Spatie\Activitylog\Traits\LogsActivity;
use Modules\Subscription\Entities\Subscription;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Branch extends Model
{
    use HasFactory, HasTranslations, LogsActivity;

    protected $fillable = ['title', 'slug', 'phone', 'secondary_phone', 'address', 'is_active', 'image', 'lat', 'long', 'qr_code', 'delivery_fee_fixed', 'delivery_fee_per_km', 'delivery_fee_method', 'scan_counter', 'city'];
    public $translatable = ['title', 'address', 'city'];
    protected static $logName = 'Branch';
    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['scan_counter','updated_at'];
    protected static $logOnlyDirty = true;

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getImageAttribute($value)
    {
        if ($value != null && $value != '') {
            return asset('uploads/branch/' . $value);
        }
        return $value;
    }

    public function shortUrl()
    {
        return $this->hasOne(ShortUrl::class);
    }

    public function deliveryCharges()
    {
        return $this->hasMany(DeliveryCharge::class);
    }

    public function deliveryAreas()
    {
        return $this->hasMany(DeliveryArea::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'branch_products')->withPivot('price', 'is_active')->withTimestamps();
    }

    public function branchProducts()
    {
        return $this->hasMany(Product::class, 'branch_id');
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    public function orderMethods(): BelongsToMany
    {
        return $this->belongsToMany(OrderMethod::class, 'branch_order_methods');
    }

    public function paymentMethods(): BelongsToMany
    {
        return $this->belongsToMany(PaymentMethod::class, 'branch_payment_methods');
    }

    public function workingHours()
    {
        return $this->hasMany(WorkingHour::class);
    }

    public function settings(): HasOne
    {
        return $this->hasOne(BranchSetting::class);
    }

    public function qrSetting(): HasOne
    {
        return $this->hasOne(BranchQrSetting::class);
    }

    public function orderDiscounts()
    {
        return $this->hasMany(BranchOrderDiscount::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->where('is_active', 1)->latestOfMany();
    }

    public function scopeAvailable($query)
    {
        if (auth('admin')->check()) {
            $admin = auth('admin')->user();
            if ($admin->hasRole('Super Admin')) {
                // show all data
            } else if ($admin->hasRole('Branch Manager')) {
                $query->where('id', $admin->branch_id);
            }
        }
    }
}
