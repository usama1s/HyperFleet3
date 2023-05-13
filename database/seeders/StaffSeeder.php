<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $staff_role = [
            "operations",
            "manager",
            "accounts",
        ];

        $i = 1;
        foreach ($staff_role as $role) {
                factory(Staff::class)->create()->each(function ($staff) use ($i, $role) {

                   $staff = Staff::find($i);
                   $user = User::find($staff->user_id);
                   $user->role = "2";
                   $staff->image = $i.".jpg";

                   $staff->save();
                   $user->save();

                   $user->assignRole($role);

                 });


           $i++;

        }
    }
}
