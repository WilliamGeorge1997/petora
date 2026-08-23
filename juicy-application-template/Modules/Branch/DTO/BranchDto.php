<?php


namespace Modules\Branch\DTO;

use Illuminate\Support\Str;
use Modules\Branch\Entities\Branch;


class BranchDto
{

    public $title;
    public $slug;
    public $image;
    public $phone;
    public $secondary_phone;
    public $address;
    public $city;
    public $lat;
    public $long;
    public $is_active;
    public $qr_code;
    public $delivery_fee_fixed;
    public $delivery_fee_per_km;
    public $delivery_charges;
    public $delivery_areas;
    public $order_methods;
    public $payment_methods;
    public $delivery_fee_method;
    public $working_hours;

    public function __construct($request, $isUpdate = false)
    {

        $this->title = ['en' => $request->get('title_en'), 'ar' => $request->get('title_ar')];
        if (!$isUpdate)
            $this->slug = $this->uniqueSlug($request->get('title_en'));
        $this->phone = $request->get('phone');
        $this->secondary_phone = $request->get('secondary_phone');
        $this->lat = $request->get('lat');
        $this->long = $request->get('long');
        $this->address = ['en' => $request->get('address_en'), 'ar' => $request->get('address_ar')];
        $this->city = ['en' => $request->get('city_en'), 'ar' => $request->get('city_ar')];
        $this->delivery_fee_fixed = $request->get('delivery_fee_fixed');
        $this->delivery_fee_per_km = $request->get('delivery_fee_per_km');
        $this->delivery_charges = $request->get('delivery_charges');
        $this->delivery_areas = $request->get('delivery_areas');
        $this->order_methods = $request->get('order_methods');
        $this->payment_methods = $request->get('payment_methods');
        $this->delivery_fee_method = $request->get('delivery_fee_method');
        $this->working_hours = $request->get('working_hours');
        if ($request->hasFile('image'))
            $this->image = $request->file('image');
        $this->is_active = isset($request['is_active']) ? 1 : 0;
        if (!$isUpdate)
            $this->qr_code = Str::random(60);

    }


    private function uniqueSlug(?string $titleEn): string
    {
        $base = Str::slug($titleEn);
        $slug = $base;
        $i = 1;

        while (Branch::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }


    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($data['image'] == null)
            unset($data['image']);
        if ($data['delivery_fee_fixed'] == null)
            unset($data['delivery_fee_fixed']);
        if ($data['delivery_fee_per_km'] == null)
            unset($data['delivery_fee_per_km']);
        if ($data['delivery_charges'] == null)
            unset($data['delivery_charges']);
        if ($data['delivery_areas'] == null)
            unset($data['delivery_areas']);
        if ($data['slug'] == null)
            unset($data['slug']);
        if ($data['qr_code'] == null)
            unset($data['qr_code']);
        if ($data['order_methods'] == null)
            unset($data['order_methods']);
        if ($data['payment_methods'] == null)
            unset($data['payment_methods']);
        if ($data['delivery_fee_method'] == null)
            unset($data['delivery_fee_method']);
        if ($data['working_hours'] == null)
            unset($data['working_hours']);
        return $data;
    }
}
