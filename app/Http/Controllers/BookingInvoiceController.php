<?php

namespace App\Http\Controllers;

use App\Models\Booking_invoice;
use App\Models\Booking;
use App\Http\Controllers\Controller;
use App;
use PDF;
use File;
use DB;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  $invoices =  Booking_invoice::latest()->get();

        $invoices = DB::table("bookings")
        ->join("booking_invoices","booking_invoices.booking_id","=","bookings.id");
        
        
        
        if(auth()->user()->role == 3){
            $invoices = $invoices->where("bookings.supplier_id",auth()->user()->id);
        } 

        $invoices = $invoices->select('booking_invoices.*')->get();
        

        return view('pages.accounts.invoice.manage',compact('invoices'));
    }

    public function generatePDF(Booking_invoice $invoice){

        $booking = Booking::find($invoice->booking_id);
        
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pages.accounts.invoice.view',compact('booking','invoice'));

        return $pdf->download('invoice#'.$invoice->id.'.pdf');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Booking_invoice  $booking_invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Booking_invoice $booking_invoice)
    {
        $booking = Booking::find($booking_invoice->booking_id);
        $invoice = $booking_invoice;
        return view('pages.accounts.invoice.view',compact('booking','invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Booking_invoice  $booking_invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking_invoice $booking_invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking_invoice  $booking_invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking_invoice $booking_invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Booking_invoice  $booking_invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking_invoice $invoice)
    {
        if(!is_null($invoice)){

            $path = public_path('assets/invoices/').'invoice-'.$invoice->id.".pdf";

            File::delete($path);
            $invoice->delete();

            return back()
            ->with('success', 'Invoice Deleted');
        }else{
            abort(403, 'Payment Not Found.');
        }
    }

    public function bulkDestroy(Request $request){
        //bulk Destroy
        $count = 0;
        if(!is_null($request->seleted_id)){

            foreach($request->seleted_id as $invoice){

                $invoice = Booking_invoice::find($invoice);

                $path = public_path('assets/invoices/').'invoice-'.$invoice->id.".pdf";
                if(is_file($path)){

                    File::delete($path);
                }
                $invoice->delete();
                $count++;
            }

            return back()->with("success",$count. Str::plural(' record',$count).' deleted');

        }else{
            abort(403, 'invoices Not Found.');
        }

    }
}
