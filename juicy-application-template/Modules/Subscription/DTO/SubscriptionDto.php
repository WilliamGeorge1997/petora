<?php

namespace Modules\Subscription\DTO;

use Modules\Package\Service\PackageService;

class SubscriptionDto
{
    public $package_id;
    public $branch_id;

    public function __construct($request, $branch)
    {
        $this->package_id = $request->get('package_id');
        $this->branch_id = $branch->id;
    }

    public function dataFromRequest()
    {
        $dataFromRequest = json_decode(json_encode($this), true);
        $data = array_merge($dataFromRequest, $this->handleData());
        return $data;
    }

    private function package()
    {
        return (new PackageService())->findById($this->package_id);
    }

    private function handleData()
    {
        $package = $this->package();
        $data['price'] = $package->discounted_price ?? $package->price;
        $data['start_date'] = now();
        $data['end_date'] = now()->addMonths($package->duration_months);
        $data['duration_months'] = $package->duration_months;
        $data['product_count'] = $package->product_count;
        return $data;
    }
}
