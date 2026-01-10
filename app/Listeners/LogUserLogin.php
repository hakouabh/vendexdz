<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserActivityLog;
use Illuminate\Support\Carbon;
class LogUserLogin
{
    /**
     * Create the event listener.
     */
    public function handle(Login $event)
    {
     //  $lastLog = UserActivityLog::where('user_id', $event->user->id)
      //  ->where('status', 'login')
      //  ->latest('logged_at')
       // ->first();
//
    // لو مافي سجل سابق أو الفرق 10 ثواني أو أكثر → نسجل
    //if (! $lastLog || $lastLog->logged_at->diffInSeconds(Carbon::now()) >= 10) {
      //  UserActivityLog::create([
      //      'user_id'   => $event->user->id,
      //      'logged_at' => now(),
      //      'status'    => 'login',
     //   ]);
   //   }
    }
}
