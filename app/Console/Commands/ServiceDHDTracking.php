<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ServiceDHDTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:dhd-tracking';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       sleep(10); 
    
    }
}
