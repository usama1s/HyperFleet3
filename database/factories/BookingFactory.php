<?php


namespace Database\Factories;

use App\Models\User;
use App\Models\VehicleClass;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement(array('point-2-point','hourly'));

        if($type == "point-2-point"){
            $drop_off = $this->faker->streetAddress;
            $duration = null;
        } else{
            $drop_off = null;
            $duration =  $this->faker->randomElement(array('6','8','10'));
        }

        $d =  (Array) $this->faker->dateTimeThisMonth($max = 'now', $timezone = null);
        $date = new Carbon($d['date']);

        $d_Type = ['day','month', 'year'];

        $date->add(1,$this->faker->randomElement($d_Type));



        $price =  $this->faker->numberBetween($min = 20, $max = 400);

        $customer = User::inRandomOrder()
        ->first();

        return [
            'type' => $type,
            'status' => 'open',
            'pickup_point' => $this->faker->streetAddress,
            'drop_off' =>  $drop_off,
            'pickup_date' =>$date,
            'pickup_time' => $this->faker->time,
            'duration' =>  $duration,
            'no_of_adults' => $this->faker->randomDigit,
            'no_of_bags' => $this->faker->randomDigit,
            'price' => $price,
            'grand_price' => $price,
            'v_class' => VehicleClass::all()->random(1)->first()->id,
            'service_type' => $this->faker->randomElement(array('airport','fullday','bus stop')),
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'email' => $customer->email,
            'contact_no' => $customer->contact_no,
            'pickup_sign' => $this->faker->randomDigit,
            'special_instructions' => $this->faker->sentence,
            'payment_method' => 'cash',
            'customer_id' => $customer->id

        ];
    }
}
