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
        $data = $request->merge(['pagination_type' => 'cursor'])->all();
        $stores = $this->storeService->active($data);
        return success(true, __('store::message.fetched'), $stores);
    }
}
