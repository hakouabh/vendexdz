<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LinkManagementController extends Controller
{
    public function index()
    {
      
        return view('manager.link.index');
    }

}
