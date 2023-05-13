<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $details;
    protected $emailClass;

    public function __construct($details,$emailClass)
    {
        $this->details = $details;
        $this->emailClass = $emailClass;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       

        Mail::to($this->details['to'])->send( $this->emailClass );
    }
}
