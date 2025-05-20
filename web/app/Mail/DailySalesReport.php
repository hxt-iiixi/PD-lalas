<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Sale;

class DailySalesReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function build()
    {
        return $this->subject('Daily Sales Report')
                    ->markdown('emails.daily_sales');
    }
}
