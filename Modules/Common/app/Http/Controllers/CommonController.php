<?php

namespace Modules\Common\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Common\Services\CommonService;

#[Middleware('auth:admin')]
#[Middleware('role:Super Admin')]
#[Middleware('permission:Index-setting', only: ['index'])]
#[Middleware('permission:Edit-setting', only: ['store'])]
class CommonController extends Controller
{
    public function __construct(private CommonService $commonService) {}

    public function index()
    {
        $settings = $this->commonService->findAll();
        return view('common::settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        $this->commonService->save($data);
        return back()->with('updated', 'updated');
    }

    public function logs()
    {
        $data = ['paginated' => 50];
        $logs = $this->commonService->logs($data);
        return view('common::logs.index', compact('logs'));
    }
}
