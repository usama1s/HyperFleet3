<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ChangeSupplierPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'driver-create',
            'driver-view',
            'driver-edit',
            'driver-delete',

            'vehicle-create',
            'vehicle-view',
            'vehicle-edit',
            'vehicle-delete',

            //  'supplier-create',
            // 'supplier-view',
            // 'supplier-edit',
            // 'supplier-delete',

            //  'shift-create',
            // 'shift-view',
            // 'shift-edit',
            // 'shift-delete',

            // 'accounts-create',
            // 'accounts-view',
            // 'accounts-edit',
            // 'accounts-delete',

            // 'staff-create',
            // 'staff-view',
            // 'staff-edit',
            // 'staff-delete',

            // 'role-create',
            // 'role-view',
            // 'role-edit',
            // 'role-delete',

            // 'booking-create',
            'booking-view',
            // 'booking-edit',
            // 'booking-delete',

            // 'permission-create',
            // 'permission-view',
            // 'permission-edit',
            // 'permission-delete',

            'pricing-create',
            'pricing-view',
            'pricing-edit',
            'pricing-delete',

            // 'booking-agent-create',
            // 'booking-agent-view',
            // 'booking-agent-edit',
            // 'booking-agent-delete',


         ];

         $supplier_role = Role::where(['name' => 'supplier'])->first();
         $old_permissions = Permission::get();
         $supplier_role->revokePermissionTo($old_permissions);

        foreach ($permissions as $permission){
            $supplier_role->givePermissionTo($permission);

        }

    }
}
