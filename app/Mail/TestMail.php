<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

   
    public $invoice;

    public function __construct($invoice)
    {

        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $invoice_name = "invoice-".$this->invoice->id.'.pdf';

        $invoices_path = public_path('assets/invoices/');
        return $this->markdown('emails.test')->attach($invoices_path.$invoice_name,[
            "as" => $invoice_name,
            "mime" => "application/pdf"
        ])
        ->with([
                 "invoice_path" => asset($invoices_path.$invoice_name),
                 "invoice" => $this->invoice
         ]);

        //  return $this->markdown('emails.test')->with([
        //      "invoice_path" => asset('public/invoices/'.$invoice_name)
        //  ]);


    }
}
