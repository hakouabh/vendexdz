<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    // === 1. Stores View ===
    public function stores()
    {
        // قد يكون هناك فلتر مختلف للمانجر
        // $stores = User::where('role', 'store')->where('manager_id', auth()->id())->get();
        return view('manager.users.stores');
    }

    // === 2. Agents View ===
    public function agents()
    {
        return view('manager.users.agents');
    }
    
    public function updateAgent(Request $request, $id)
    {
        // منطق تعديل الوكيل من طرف المانجر (قد يكون محدود الصلاحيات)
        return back()->with('success', 'Agent updated by Manager');
    }
}