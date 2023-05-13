<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AddPriceSchemePermissionToDatabase extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'pricing-create',
            'pricing-view',
            'pricing-edit',
            'pricing-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $admin_role = Role::where(['name' => 'admin'])->first();
        $admin_role->givePermissionTo($permissions);

        $supplier_role = Role::where(['name' => 'supplier'])->first();
        $supplier_role->givePermissionTo($permissions);





    }
}
