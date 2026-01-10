<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Fortify;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
      
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        $user = auth()->user();

       
        if ($user->roles()->where('roles.rid', 1)->exists()) {
            return redirect()->intended('/waitactivation');
        }

        if ($user->roles()->where('roles.rid', 2)->exists()) {
            return redirect()->intended('/admin/dashboard');
        }
        if ($user->roles()->where('roles.rid', 3)->exists()) {
            return redirect()->intended('/manager/dashboard');
        }
        if ($user->roles()->where('roles.rid', 4)->exists()) {
            return redirect()->intended('/agent/dashboard');
        }
        if ($user->roles()->where('roles.rid', 5)->exists()) {
            return redirect()->intended('/store/dashboard');
        }

        return redirect()->intended('/waitactivation');
    }
}