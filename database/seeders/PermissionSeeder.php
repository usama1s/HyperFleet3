<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
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

            'supplier-create',
           'supplier-view',
           'supplier-edit',
           'supplier-delete',

            'shift-create',
           'shift-view',
           'shift-edit',
           'shift-delete',

           'accounts-create',
           'accounts-view',
           'accounts-edit',
           'accounts-delete',

           'staff-create',
           'staff-view',
           'staff-edit',
           'staff-delete',

           'role-create',
           'role-view',
           'role-edit',
           'role-delete',

           'booking-create',
           'booking-view',
           'booking-edit',
           'booking-delete',

           'permission-create',
           'permission-view',
           'permission-edit',
           'permission-delete',


        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
