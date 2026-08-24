<?php

namespace Modules\Common\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Modules\Common\Services\CommonService;
use Illuminate\Routing\Controllers\Middleware;

class CommonController implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth:admin',
            'role:Super Admin',
            new Middleware('permission:Index-setting', only: ['index']),
            new Middleware('permission:Edit-setting', only: ['store'])
        ];
    }

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
