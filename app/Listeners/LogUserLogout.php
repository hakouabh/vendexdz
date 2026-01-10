<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserActivityLog;
use Illuminate\Support\Carbon;
class LogUserLogout
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
   public function handle(Logout $event)
    {
      
      // $lastLog = UserActivityLog::where('user_id', $event->user->id)
      //  ->where('status', 'logout')
      //  ->latest('logged_at')
     //   ->first();

    // لو مافي سجل سابق أو الفرق 10 ثواني أو أكثر → نسجل
    //if (! $lastLog || $lastLog->logged_at->diffInSeconds(Carbon::now()) >= 10) {
      //  UserActivityLog::create([
      //      'user_id'   => $event->user->id,
      //      'logged_at' => now(),
      //      'status'    => 'logout',
      //  ]);
     // }
    }
    
}
