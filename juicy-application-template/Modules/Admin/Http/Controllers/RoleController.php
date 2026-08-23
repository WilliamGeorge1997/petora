<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Admin\DTO\AdminDto;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Service\AdminService;
use Modules\Admin\Service\RoleService;
use Modules\Common\Helper\UploaderHelper;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public $roleAction;

    public function __construct(RoleService $roleAction)
    {
        $this->middleware(['auth:admin', 'prevent-back-history']);
        $this->roleAction = $roleAction;
        $this->middleware('permission:Index-role|Create-role|Edit-role|Delete-role', ['only' => ['index','store']]);
        $this->middleware('permission:Create-role', ['only' => ['create','store']]);
        $this->middleware('permission:Edit-role', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete-role', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $roles = $this->roleAction->findAll(['id', 'name', 'created_at']);
        $permissions = $this->roleAction->findAllPermission();
        if($request->ajax()){
            return response()->json(['data' => $roles]);
        }

//        return $permissions;
        return view('admin::roles.index', ['roles' => $roles, 'cat_permissions' => $permissions]);
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $data = $request->except('_token');
        $role = $this->roleAction->saveRole($data);
        return response()->json(["role"=> $role]);
    }


    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $role = $this->roleAction->findById($id);
        $permissions = $this->roleAction->findAllPermission();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        return view('admin::roles.edit',['role' => $role,'cat_permissions' => $permissions,'rolePermissions' => $rolePermissions]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request,$id)
    {

        $request->validate([
            'name' => 'required',
        ]);

        $data = $request->except('_token');
        $role = $this->roleAction->update($data, $id);
        return redirect('admin/roles')->with('updated','updated');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->roleAction->delete($id);
        return response()->json(['data' => 'success'],200);
    }
}
