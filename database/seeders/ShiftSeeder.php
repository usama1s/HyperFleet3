<?php
namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shifts_list = [
            ["Morning Shift","09:00:00", "15:00:00"],
            ["2nd Shift","15:00:00", "21:00:00"],
            ["Evening Shift","21:00:00", "03:00:00"],
            ["Night Shift","03:00:00", "09:00:00"]
        ];

        foreach ($shifts_list as $shift) {
            Shift::create(
                 [
                    'name' => $shift[0],
                    'start' => $shift[1],
                    'end' => $shift[2]
                 ]
             );
        }
    }
}
