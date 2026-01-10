<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductsManagementController extends Controller
{
    public function index()
    {   
        return view('store.products.index');
    }
}
