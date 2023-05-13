<?php
namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = array(
            'company_name',
            'favicon',
            'logo',
            'mail_server_host',
            'mail_server_port',
            'mail_username',
            'mail_password',
            'region',
            'timezone',
            'assignDriverToBookingDesign',
            'vat',
            'currency',
            'company_address',
            'company_contact',
            'company_email',
            );

            foreach ($settings as $fields) {
                Setting::create(
                     [
                        'meta' => $fields,
                    ]
                 );
            }
    }
}
