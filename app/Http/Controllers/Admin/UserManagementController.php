<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
      
        return view('admin.users.index');
    }

    public function login($userId)
    {
        auth('web')->loginUsingId($userId);
        return to_route('store-dashboard');
    }
  
}