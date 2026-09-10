<?php

namespace Modules\Service\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    public function index()
    {
        return view('service::index');
    }
}
