<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Mail\DailySalesReport;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDailySalesEmail extends Command
{
    protected $signature = 'email:daily-sales';
    protected $description = 'Send daily sales log to admin at midnight';

    public function handle()
    {
        $yesterday = Carbon::yesterday();
        $sales = Sale::whereDate('created_at', $yesterday)->with('product')->get();

        if ($sales->isEmpty()) {
            $this->info('No sales to send.');
            return;
        }

        // ✅ FIXED: Use correct variable
        Mail::to('kdvenecia@gmail.com')->send(new DailySalesReport($sales));
        \Log::debug('Sales data:', $sales->toArray());


        $this->info('Daily sales email sent.');
    }

}
