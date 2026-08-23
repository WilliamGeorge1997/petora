<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Entities\Admin;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //        Model::unguard();

        $admin = $this->adminCreation();
        // $admin2 = $this->admin2Creation();
        $permissions = $this->permissionCreation();
        $role = $this->roleCreation();
        $role2 = $this->role2Creation();
        $admin->assignRole($role);
        // $admin2->assignRole($role2);
        // $this->call("OthersTableSeeder");
    }

    function adminCreation()
    {
        return $admin = Admin::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456789'),
            'phone' => '1235464654'
        ]);
    }

    // function admin2Creation (){
    //     return $admin =  Admin::create([
    //         'name' => 'osama',
    //         'email' => 'osama@osama.com',
    //         'password' => bcrypt('123456789'),
    //         'phone' => '12354654654'
    //     ]);

    // }

    function permissionCreation()
    {
        $permissions = [
            ['Index-admin', 'Admin', 'Index'],
            ['Create-admin', 'Admin', 'Create'],
            ['Edit-admin', 'Admin', 'Edit'],
            ['Delete-admin', 'Admin', 'Delete'],

            ['Index-role', 'Roles', 'Index'],
            ['Create-role', 'Roles', 'Create'],
            ['Edit-role', 'Roles', 'Edit'],
            ['Delete-role', 'Roles', 'Delete'],

            ['Index-branch', 'Branch', 'Index'],
            ['Create-branch', 'Branch', 'Create'],
            ['Edit-branch', 'Branch', 'Edit'],
            ['Delete-branch', 'Branch', 'Delete'],

            ['Index-category', 'Category', 'Index'],
            ['Create-category', 'Category', 'Create'],
            ['Edit-category', 'Category', 'Edit'],
            ['Delete-category', 'Category', 'Delete'],

            ['Index-product', 'Product', 'Index'],
            ['Create-product', 'Product', 'Create'],
            ['Edit-product', 'Product', 'Edit'],
            ['Delete-product', 'Product', 'Delete'],

            ['Index-addon', 'Addon', 'Index'],
            ['Create-addon', 'Addon', 'Create'],
            ['Edit-addon', 'Addon', 'Edit'],
            ['Delete-addon', 'Addon', 'Delete'],

            ['Index-coupon', 'Coupon', 'Index'],
            ['Create-coupon', 'Coupon', 'Create'],
            ['Edit-coupon', 'Coupon', 'Edit'],
            ['Delete-coupon', 'Coupon', 'Delete'],

            ['Index-ordermethod', 'OrderMethod', 'Index'],
            ['Create-ordermethod', 'OrderMethod', 'Create'],
            ['Edit-ordermethod', 'OrderMethod', 'Edit'],
            ['Delete-ordermethod', 'OrderMethod', 'Delete'],

            ['Index-paymentmethods', 'PaymentMethods', 'Index'],
            ['Create-paymentmethods', 'PaymentMethods', 'Create'],
            ['Edit-paymentmethods', 'PaymentMethods', 'Edit'],
            ['Delete-paymentmethods', 'PaymentMethods', 'Delete'],

            ['Index-setting', 'Setting', 'Index'],
            ['Create-setting', 'Setting', 'Create'],
            ['Edit-setting', 'Setting', 'Edit'],
            ['Delete-setting', 'Setting', 'Delete'],

            ['Index-orderstatus', 'OrderStatus', 'Index'],
            ['Create-orderstatus', 'OrderStatus', 'Create'],
            ['Edit-orderstatus', 'OrderStatus', 'Edit'],
            ['Delete-orderstatus', 'OrderStatus', 'Delete'],

            ['Index-order', 'Order', 'Index'],
            ['Edit-order', 'Order', 'Edit'],

            ['Index-log', 'Log', 'Index'],

            ['Index-report', 'Report', 'Index'],

            ['Index-dashboard', 'Dashboard', 'Index'],

        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission[0], 'category' => $permission[1], 'guard_name' => 'admin', 'display' => $permission[2]]);
        }
    }

    function roleCreation()
    {
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $permissions = Permission::all();
        $role->syncPermissions($permissions);
        return $role;
    }

    function role2Creation()
    {
        $role = Role::create(['name' => 'Branch Manager', 'guard_name' => 'admin']);
        $permissions = Permission::whereNotIn('category', ['Admin', 'Roles', 'Branch', 'OrderMethod', 'PaymentMethods', 'Setting', 'OrderStatus'])
            ->get();
        $role->syncPermissions($permissions);
        return $role;
    }
}
