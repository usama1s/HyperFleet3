<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
        SettingSeeder::class,
        ShiftSeeder::class,
        VehicleClassSeeder::class,
        VehicleSeeder::class,
        RoleSeeder::class,
        PermissionSeeder::class,
        AdminSeeder::class,
        StaffSeeder::class,
        DriverSeeder::class,
        SupplierSeeder::class,
        CustomerSeeder::class,
        BookingSeeder::class,
        AssignVehicleToDriver::class,
        AddPriceSchemePermissionToDatabase::class,
        AddBookingAgentRoleAndPermissions::class,
        addCustomerPermissionToDatabase::class,
        ChangeAdminPermissions::class,
        ChangeSupplierPermissions::class,
        LanguageSuportedSeeder::class,


    ]);
    }
}
