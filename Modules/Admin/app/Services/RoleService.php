<?php


namespace Modules\Admin\Services;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function __construct(
        private Role $model = new Role(),
    ) {}

    function findAll(array $data = [], $select = ['*'])
    {
        $query = $this->model->query();
        return getCaseCollection($query, $data);
    }

    function findAllPermission($select = ['*'])
    {
        return Permission::all()->groupBy('category');
    }


    function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    function save(array $data)
    {
        $role = $this->model->create(['name' => $data['name']]);
        $permissions = str_replace('[', '', $data['permission']);
        $permissions = str_replace(']', '', $permissions);
        $permissions = explode(',', $permissions);
        $role->syncPermissions($permissions);
        return $role;
    }


    function update(array $data, int $id)
    {
        $role = $this->findById($id);
        $role->name = $data['name'];
        $role->save();
        $role->syncPermissions($data['permission']);
        return $role;
    }

    function delete(int $id)
    {
        DB::table("roles")->where('id', $id)->delete();
    }
}
