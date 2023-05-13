<?php
namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingLogs;
use App\Models\Booking_invoice;
use Illuminate\Database\Seeder;


class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<=20; $i++){
            Booking::factory()->create()->each(function ($booking) use ($i){

                $booking = Booking::find($i);

                $invoice = new Booking_invoice;
                $invoice->booking_id = $booking->id;
                $invoice->status = "unpaid";
                $invoice->save();

                $log = new BookingLogs;
                $log->booking_id = $booking->id;
                $log->log = "add-booking";
                $log->updated_by = 1;
                $log->save();

                // if($i < 5){
                    $booking->supplier_id = null;
                // }else{
                //     $booking->supplier_id = Supplier::all()->random(1)->first()->user_id;
                // }

                $booking->save();

             });



        }
    }
}
