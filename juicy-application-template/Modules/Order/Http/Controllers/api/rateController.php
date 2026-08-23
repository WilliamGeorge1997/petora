<?php

namespace Modules\Order\Http\Controllers\api;

use Illuminate\Routing\Controller;
use Modules\Order\Http\Requests\RateRequest;
use Modules\Order\Service\RateService;

class rateController extends Controller
{
    private $rateService;
    public function __construct(RateService $rateService)
    {
        $this->rateService = $rateService;
    }

    public function store(RateRequest $request)
    {
        $previous_rate = $this->rateService->findBy('order_id',$request['order_id']);
        if($previous_rate->count() >= 1) return return_msg(false, 'Order already rated',null,'forbidden');
        $rate = $this->rateService->save($request->toArray());
        return return_msg(true, 'Rate Created Successfully', $rate);
    }
}
