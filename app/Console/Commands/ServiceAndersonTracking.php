<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ServiceAndersonTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:anderson-tracking';

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
         try {
        $logFile = storage_path('logs/raw_http_requests.txt');
        $rawHttpContent = 'oussama';

        file_put_contents($logFile, $data, FILE_APPEND);
    } catch (\Exception $e) {
        Log::error("Failed to write raw request to file: " . $e->getMessage());
    }
    
    
    }
}
