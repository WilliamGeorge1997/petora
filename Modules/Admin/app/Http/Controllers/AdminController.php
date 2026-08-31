<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Modules\Admin\DTOs\AdminDto;
use Modules\Admin\Services\AdminService;
use Modules\Admin\Services\RoleService;
use Modules\Admin\ViewModel\AdminViewModel;

#[Middleware('auth:admin')]
#[Middleware('permission:Index-admin|Create-admin|Edit-admin|Delete-admin', only: ['index', 'store'])]
#[Middleware('permission:Create-admin', only: ['create', 'store'])]
#[Middleware('permission:Edit-admin', only: ['edit', 'update', 'activate'])]
#[Middleware('permission:Delete-admin', only: ['destroy'])]
class AdminController extends Controller
{
    public function __construct(private AdminService $adminService) {}

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function dashboard(Request $request)
    {
        return view('admin::dashboard');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $data['paginated'] = 50;
            $relations = ['roles:name'];
            $admins = $this->adminService->findAll($data, $relations);

            return response()->json(['data' => $admins->items()]);
        }

        $roles = (new RoleService())->findAll(['id', 'name']);

        return view('admin::admins.index', ['roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $roles = (new RoleService())->findAll(['id', 'name']);
        return view('admin::admins.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = (new AdminDto($request))->dataFromRequest();
        $validation = $this->validateStore($data);
        if ($validation->fails()) return redirect()->back()->withInput()->withErrors($validation);
        $admin = $this->adminService->save($data);
        return redirect('admin/admins')->with('created', 'created');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $admin = $this->adminService->findById($id);
        $roles = (new RoleService())->findAll(['id', 'name']);
        $userRole = $admin->roles->pluck('name', 'name')->all();
        $viewModel = new AdminViewModel();
        return view('admin::admins.edit', compact('admin', 'roles', 'userRole', 'viewModel'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $data = (new AdminDto($request))->dataFromRequest();
        $validation = $this->validateUpdate($data, $id);
        if ($validation->fails()) return redirect()->back()->withInput()->withErrors($validation);
        $admin = $this->adminService->update($id, $data);
        return redirect('admin/admins')->with('updated', 'updated');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, Request $request)
    {
        $this->adminService->delete($id);
        return response()->json(['data' => 'success'], 200);
    }

    public function activate($id)
    {
        $this->adminService->activate($id);
        return redirect('admin/admins')->with('updated', 'updated');
    }
}
