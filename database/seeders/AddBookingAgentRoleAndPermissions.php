<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddBookingAgentRoleAndPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'booking-agent-create',
            'booking-agent-view',
            'booking-agent-edit',
            'booking-agent-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $admin_role = Role::where(['name' => 'admin'])->first();
        $admin_role->givePermissionTo($permissions);

        $bookingAgent = Role::create(['name' => 'booking-agent']);

    }
}
