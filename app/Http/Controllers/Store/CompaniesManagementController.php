<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompaniesManagementController extends Controller
{
    public function index()
    {
        return view('store.companies.index');
    }
}
