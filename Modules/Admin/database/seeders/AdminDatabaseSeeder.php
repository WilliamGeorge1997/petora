<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\Models\Admin;
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
        $admin = $this->superAdminCreation();
        $this->permissionCreation();
        $role = $this->superAdminRoleCreation();
        $this->companyManagerRoleCreation();
        $this->storeManagerRoleCreation();
        $this->clinicManagerRoleCreation();
        $admin->assignRole($role);
    }

    function superAdminCreation()
    {
        return Admin::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456789'),
            'phone' => '0123456789'
        ]);
    }

    function permissionCreation()
    {
        $permissions = [
            ['Index-admin', 'Admin', 'Index'],
            ['Create-admin', 'Admin', 'Create'],
            ['Edit-admin', 'Admin', 'Edit'],
            ['Delete-admin', 'Admin', 'Delete'],

            ['Index-role', 'Role', 'Index'],
            ['Create-role', 'Role', 'Create'],
            ['Edit-role', 'Role', 'Edit'],
            ['Delete-role', 'Role', 'Delete'],

            ['Index-country', 'Country', 'Index'],
            ['Create-country', 'Country', 'Create'],
            ['Edit-country', 'Country', 'Edit'],
            ['Delete-country', 'Country', 'Delete'],

            ['Index-client', 'Client', 'Index'],
            ['Create-client', 'Client', 'Create'],
            ['Edit-client', 'Client', 'Edit'],
            ['Delete-client', 'Client', 'Delete'],

            ['Index-doctor', 'Doctor', 'Index'],
            ['Create-doctor', 'Doctor', 'Create'],
            ['Edit-doctor', 'Doctor', 'Edit'],
            ['Delete-doctor', 'Doctor', 'Delete'],

            ['Index-driver', 'Driver', 'Index'],
            ['Create-driver', 'Driver', 'Create'],
            ['Edit-driver', 'Driver', 'Edit'],
            ['Delete-driver', 'Driver', 'Delete'],

            ['Index-company', 'Company', 'Index'],
            ['Create-company', 'Company', 'Create'],
            ['Edit-company', 'Company', 'Edit'],
            ['Delete-company', 'Company', 'Delete'],

            ['Index-store', 'Store', 'Index'],
            ['Create-store', 'Store', 'Create'],
            ['Edit-store', 'Store', 'Edit'],
            ['Delete-store', 'Store', 'Delete'],

            ['Index-clinic', 'Clinic', 'Index'],
            ['Create-clinic', 'Clinic', 'Create'],
            ['Edit-clinic', 'Clinic', 'Edit'],
            ['Delete-clinic', 'Clinic', 'Delete'],

            ['Index-category', 'Category', 'Index'],
            ['Create-category', 'Category', 'Create'],
            ['Edit-category', 'Category', 'Edit'],
            ['Delete-category', 'Category', 'Delete'],

            ['Index-product', 'Product', 'Index'],
            ['Create-product', 'Product', 'Create'],
            ['Edit-product', 'Product', 'Edit'],
            ['Delete-product', 'Product', 'Delete'],

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

            ['Index-orderstatus', 'OrderStatus', 'Index'],
            ['Create-orderstatus', 'OrderStatus', 'Create'],
            ['Edit-orderstatus', 'OrderStatus', 'Edit'],
            ['Delete-orderstatus', 'OrderStatus', 'Delete'],

            ['Index-setting', 'Setting', 'Index'],
            ['Create-setting', 'Setting', 'Create'],
            ['Edit-setting', 'Setting', 'Edit'],
            ['Delete-setting', 'Setting', 'Delete'],

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

    function superAdminRoleCreation()
    {
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $permissions = Permission::all();
        $role->syncPermissions($permissions);
        return $role;
    }

    function companyManagerRoleCreation()
    {
        $role = Role::create(['name' => 'Company Manager', 'guard_name' => 'admin']);
        // $permissions = Permission::whereNotIn('category', ['Admin', 'Roles', 'Branch', 'OrderMethod', 'PaymentMethods', 'Setting', 'OrderStatus'])
        //     ->get();
        // $role->syncPermissions($permissions);
        return $role;
    }

    function storeManagerRoleCreation()
    {
        $role = Role::create(['name' => 'Store Manager', 'guard_name' => 'admin']);
        // $permissions = Permission::whereNotIn('category', ['Admin', 'Roles', 'Branch', 'OrderMethod', 'PaymentMethods', 'Setting', 'OrderStatus'])
        //     ->get();
        // $role->syncPermissions($permissions);
        return $role;
    }

    function clinicManagerRoleCreation()
    {
        $role = Role::create(['name' => 'Clinic Manager', 'guard_name' => 'admin']);
        // $permissions = Permission::whereNotIn('category', ['Admin', 'Roles', 'Branch', 'OrderMethod', 'PaymentMethods', 'Setting', 'OrderStatus'])
        //     ->get();
        // $role->syncPermissions($permissions);
        return $role;
    }
}
