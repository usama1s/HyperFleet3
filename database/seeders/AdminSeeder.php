<?php
namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin_role = Role::create(['name' => 'admin']);
        // $admin_role->givePermissionTo($permissions);
        $permissions = Permission::get();
        foreach ($permissions as $permission){
            $admin_role->givePermissionTo($permission);
        }

        $admin = User::create(
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@gmail.com',
                'contact_no' => '1231341',
                'role' => '1',
                'email_verified_at' => now(),
                'password' => Hash::make('admin'), // secret
            ]
        );

        $admin_info = new Admin;
        $admin_info->user_id = $admin->id;
        $admin_info->credit = 0;
        $admin_info->save();

        $admin->assignRole('admin');

    }
}
