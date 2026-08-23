<?php


namespace Modules\Admin\Service;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{

    protected $RoleModel;

    public function __construct()
    {
        $this->RoleModel = new Role();
    }

    function findAll($select = ['*'])
    {
        $roles = $this->RoleModel->orderBy('id', 'ASC')->get($select);
        return $roles;
    }

    function findAllPermission($select = ['*'])
    {
        $permissions = Permission::all()->groupBy('category');
        return $permissions;
    }




    function findById($id)
    {
        $role =  $this->RoleModel->find($id);
        return $role;
    }

    function saveRole($data)
    {

        $role = $this->RoleModel->create(['name' => $data['name']]);
        $permissions = str_replace('[', '', $data['permission']);
        $permissions = str_replace(']', '', $permissions);
        $permissions = explode(',', $permissions);
        $role->syncPermissions($permissions);
        return $role;
    }


    function update($roleData, $id)
    {
        $role = $this->RoleModel->find($id);
        $role->name = $roleData['name'];
        $role->save();
        $role->syncPermissions($roleData['permission']);
        return $role;
    }

    function delete($id)
    {
        DB::table("roles")->where('id',$id)->delete();
    }


}
