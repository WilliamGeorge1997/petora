<?php

namespace Modules\Branch\DTO;

use Illuminate\Http\Request;

class RegistrationDto
{
    public $name;
    public $address;
    public $phone;
    public $theme;
    public $is_order_enabled;
    public $manager_name;
    public $manager_phone;
    public $location_url;
    public $working_from;
    public $working_to;
    public $is_working_24_hours;

    public function __construct(Request $request)
    {
        $this->name = ['ar' => $request->name_ar, 'en' => $request->name_en];
        $this->address = ['ar' => $request->address_ar, 'en' => $request->address_en];
        $this->phone = $request->phone;
        $this->theme = $request->theme;
        $this->is_order_enabled = $request->has('is_order_enabled') ? 1 : 0;
        $this->manager_name = $request->manager_name;
        $this->manager_phone = $request->manager_phone;
        $this->location_url = $request->location_url;
        $this->working_from = $request->working_from;
        $this->working_to = $request->working_to;
        $this->is_working_24_hours = $request->has('is_working_24_hours') ? 1 : 0;
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);
        if ($data['location_url'] == null)
            unset($data['location_url']);
        if ($data['working_from'] == null)
            unset($data['working_from']);
        if ($data['working_to'] == null)
            unset($data['working_to']);
        return $data;
    }
}
