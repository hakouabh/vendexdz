<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillsController extends Controller
{
   public function index()
    {   
        return view('agent.bills');
    }
}
