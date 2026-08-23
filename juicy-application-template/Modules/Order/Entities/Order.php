<?php

namespace Modules\Order\Entities;

use Modules\Branch\Entities\Branch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class Order extends Model
{
    use HasFactory, HasTranslations;

    const DISCOUNT_WITH_COUPON = 1;
    const DISCOUNT_WITH_POINTS = 2;
    const DISCOUNT_WITH_BRANCH = 3;

    protected $fillable = [
        'uuid',
        'order_no',
        'subtotal',
        'discount',
        'discount_type',
        'tax',
        'service',
        'total',
        'quantity',
        'client_id',
        'notes',
        'order_status_id',
        'branch_id',
        'payment_method_id',
        'order_method_id',
        'coupon_id',
        'car_id',
        'address_id',
        'delivery_fee',
        'driver_id',
        'phone',
        'link_code',
        'car_no',
        'car_color',
        'parking_no',
        'fcm_token',
        'address',
        'lat',
        'long',
        'lang',
        'table_no',
        'name'
    ];

    protected $translatable = ['address'];


    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d h:i A');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function rate()
    {
        return $this->hasOne(Rate::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function histories()
    {
        return $this->hasMany(History::class, 'order_id');
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function orderMethod()
    {
        return $this->belongsTo(OrderMethod::class);
    }

    public function scopeOrderCards($query)
    {
        return $query->whereBranchId(@Auth::user()['branch_id'])
            ->with(['details.product', 'details.addons.addonValue.addon', 'details.attributes.attributeValue.attribute', 'details.sides.sideValue.side'])->limit(8)->orderBy('id', 'DESC');
    }

    public function scopeFilter($query, $data)
    {
        return $query->when($data['branch_id'] ?? null, function ($query) use ($data) {
            $query->where('branch_id', $data['branch_id']);
        })
            ->when($data['date'] ?? null, function ($query) use ($data) {
                $query->whereDate('created_at', $data['date']);
            });
    }

    public function scopeAvailable($query){
        if(auth('admin')->check()){
            $admin = auth('admin')->user();
            if($admin->hasRole('Branch Manager')){
                $query->whereBranchId($admin->branch_id);
            }
        }
    }
    // public function getdistance()
    // {
    //     $Order = Order::whereId($this->id)->first();
    //     $AllDriver =  (new DriverService())->active();
    //     $Distance = [];
    //     foreach ($AllDriver as $key => $value) :
    //         $Distance[] =  $this->calcDistance(@$Order->branch->lat, @$Order->branch->long, $value->lat, $value->long);
    //     endforeach;

    //     return  $Distance;
    // }

    // function calcDistance($lat1, $lon1, $lat2, $lon2)
    // {
    //     if ($lat1 == 0 or $lon1 == 0  or $lat2 == 0 or $lon2 == 0)
    //         return 0;

    //     $pi80 = M_PI / 180;
    //     $lat1 *= $pi80;
    //     $lon1 *= $pi80;
    //     $lat2 *= $pi80;
    //     $lon2 *= $pi80;
    //     $r = 6372.797; // mean radius of Earth in km
    //     $dlat = $lat2 - $lat1;
    //     $dlon = $lon2 - $lon1;
    //     $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
    //     $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    //     $km = $r * $c;
    //     //echo ' '.$km;
    //     return (int)round($km);
    // }
}
