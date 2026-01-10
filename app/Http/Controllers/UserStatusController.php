<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\UserActivityLog;

class UserStatusController extends Controller
{
    public function ping(Request $request)
    {
        $user = $request->user();
        $status = $request->input('status', 'active');

        // تحديث حالة المستخدم
        //$user->update([
       //     'last_ping' => now(),
      //      'status' => $status,
      //  ]);

        // تسجيل في الأرشيف
       // UserActivityLog::create([
      //      'user_id' => $user->id,
     //       'status' => $status,
    //        'logged_at' => now(),
   //     ]);

        return response()->json(['ok' => true]);
    }
}