<?php
namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
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

        $supplier_role = Role::create(['name' => 'supplier']);
        $supplier_role->givePermissionTo($permissions);


         for($i=1; $i<=20; $i++){

            factory(Supplier::class)->create()->each(function ($supplier) use ($i) {
               $supplier = Supplier::find($i);
               $user = User::find($supplier->user_id);
               $user->role = "3";
               $supplier->image = $i.".jpg";

               $supplier->save();
               $user->save();

               $user->assignRole('supplier');

             });

        }
    }
}
