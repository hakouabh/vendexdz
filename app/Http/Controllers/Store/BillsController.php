<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillsController extends Controller
{
   public function index()
    {   
        return view('store.bills.index');
    }
}
