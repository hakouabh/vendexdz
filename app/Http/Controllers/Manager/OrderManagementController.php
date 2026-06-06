<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    public function index()
    {
        return view('manager.orders.index');
    }

    public function create()
    {
        return view('manager.orders.create');
    }
}
