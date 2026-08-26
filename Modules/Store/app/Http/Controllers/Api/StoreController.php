<?php

namespace Modules\Store\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Store\Services\StoreService;

class StoreController extends Controller
{
    public function __construct(private StoreService $storeService) {}

    public function index(Request $request)
    {
        $stores = $this->storeService->active($request->all());
        return success(true, __('store::message.fetched'), $stores);
    }
}
