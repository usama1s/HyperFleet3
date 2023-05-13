<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<=20; $i++){

            factory(User::class)->create()->each(function ($user) use ($i) {

             });

        }
    }
}
