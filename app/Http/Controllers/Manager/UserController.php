<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
      
        return view('manager.users.index');
    }

    public function impersonate(User $user)
    {
        auth()->user()->impersonate($user);
        request()->session()->regenerate();

        $dashboardRoutes = [
            2 => 'admin-dashboard',
            3 => 'manager-dashboard',
            4 => 'agent-dashboard',
            5 => 'store-dashboard',
        ];

        $roleId = auth()->user()
            ->roles()
            ->value('roles.rid');

        return to_route($dashboardRoutes[$roleId] ?? 'welcome');
    }

    public function leave()
    {
        auth()->user()->leaveImpersonation();
        request()->session()->regenerate();
        
        $dashboardRoutes = [
            2 => 'admin-dashboard',
            3 => 'manager-dashboard',
            4 => 'agent-dashboard',
            5 => 'store-dashboard',
        ];

        $roleId = auth()->user()
            ->roles()
            ->value('roles.rid');

        return to_route($dashboardRoutes[$roleId] ?? 'dashboard');
    }
  
}