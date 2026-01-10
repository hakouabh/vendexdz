<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ServiceWorldExpressTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:worldexpress-tracking';

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
       sleep(20); 
    }
}
