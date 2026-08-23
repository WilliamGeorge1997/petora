<?php

namespace Modules\Admin\Http\Controllers\api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Modules\Admin\DTO\AdminDto;
use Modules\Admin\Entities\Admin;
use Modules\Admin\Service\AdminService;
use Modules\Admin\Service\RoleService;
use Modules\Admin\Validation\AdminValidation;
use Modules\Common\Helper\UploaderHelper;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    use UploaderHelper, AdminValidation;
    private $adminService;
    public function __construct(AdminService $adminService)
    {

        // $this->middleware(['auth:admin']);
        $this->adminService = $adminService;
    }


    public function index(Request $request)
    {
        $admins = $this->adminService->findAll();
        return return_msg(true, 'All Admins', $admins);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $data = (new AdminDto($request))->adminDataFromRequest();
        $validation = $this->validateStore($data);
        if ($validation->fails()) {
            return return_msg(
                false,
                'Validation Errors',
                ['validation_errors' => $validation->getMessageBag()],
                'validation_error'
            );
        }
        $admin = $this->adminService->save($data);
        return return_msg(true, 'Admin Created Successfully', $admin, 'created');
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
        return view('admin::admins.edit', compact('admin', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $data = (new AdminDto($request))->adminDataFromRequest();
        $validation = $this->validateUpdate($data, $id);
        if ($validation->fails()) {
            return return_msg(
                false,
                'Validation Errors',
                ['validation_errors' => $validation->getMessageBag()],
                'validation_error'
            );
        }
        $admin = $this->adminService->update($id, $data);
        return return_msg(true, 'Admin Updated Successfully', $admin, 'accepted');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id, Request $request)
    {
        $this->adminService->delete($id);
        return return_msg(true, 'Admin Deleted Successfully');
    }

    public function activate($id)
    {
        $this->adminService->activate($id);
        return return_msg(true, 'Admin Updated Successfully', [], 'accepted');
    }

    public function testdata()
    {
        echo
        Auth::id();
    }
}
