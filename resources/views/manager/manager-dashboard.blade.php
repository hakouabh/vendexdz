@extends('layouts.app')

@section('title', 'Agent Workspace - Vendex')

@section('content')
    <div class="mb-8 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">My Workspace</h1>
                <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full border border-green-200 flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Online
                </span>
            </div>
            <p class="text-slate-500 text-sm font-medium">
                Friday, 26 Dec • <span class="text-indigo-600 font-bold">Shift A (Morning)</span>
            </p>
        </div>
        
        <div class="flex items-center gap-4 bg-white p-2 rounded-2xl border border-slate-100 shadow-sm">
            <div class="px-4 border-r border-slate-100">
                <p class="text-[10px] text-slate-400 font-bold uppercase">Daily Goal</p>
                <p class="text-sm font-bold text-slate-900">85/100 Calls</p>
            </div>
            <div class="px-4">
                <p class="text-[10px] text-slate-400 font-bold uppercase">Current Rank</p>
                <p class="text-sm font-bold text-indigo-600 flex items-center gap-1">
                    <i class="ri-trophy-fill text-yellow-500"></i> #3 Top Agent
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-slate-900 p-6 rounded-[24px] shadow-xl shadow-slate-200 relative overflow-hidden group">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500 rounded-full blur-[60px] opacity-30 group-hover:opacity-40 transition duration-500"></div>
            
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">My Commission</p>
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-white">
                        <i class="ri-wallet-3-line"></i>
                    </div>
                </div>
                <div class="flex items-baseline gap-1 mb-2">
                    <h3 class="text-3xl font-black text-white">42,500</h3>
                    <span class="text-sm font-bold text-slate-500">DZD</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-[10px] font-bold px-2 py-0.5 rounded-full">
                        +3,200 today
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm hover:shadow-md transition duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Confirmation Rate</p>
                <div class="w-8 h-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition">
                    <i class="ri-check-double-line"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mb-2">68%</h3>
            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-green-500 h-1.5 rounded-full" style="width: 68%"></div>
            </div>
            <p class="text-[10px] text-slate-400 mt-2 text-right">Goal: 70%</p>
        </div>

        <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm hover:shadow-md transition duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Calls Made</p>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                    <i class="ri-phone-line"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mb-2">142</h3>
            <div class="flex items-center text-xs text-blue-600 font-bold gap-1">
                <i class="ri-flashlight-fill"></i> On Fire!
            </div>
        </div>

        <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm hover:shadow-md transition duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">To Process</p>
                <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition">
                    <i class="ri-list-check"></i>
                </div>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mb-2">18</h3>
            <p class="text-[10px] text-slate-400 font-bold">Orders waiting for you</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <div class="bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm flex flex-col items-center">
            <h3 class="font-bold text-slate-900 text-lg mb-6 self-start">Call Outcomes</h3>
            
            <div class="relative w-48 h-48 rounded-full flex items-center justify-center transition-transform hover:scale-105 duration-500"
                 style="background: conic-gradient(#22c55e 0% 65%, #ef4444 65% 80%, #f59e0b 80% 100%);">
                <div class="absolute w-36 h-36 bg-white rounded-full flex flex-col items-center justify-center shadow-inner">
                    <span class="text-xs font-bold text-slate-400 uppercase">Success</span>
                    <span class="text-3xl font-black text-slate-900">65%</span>
                </div>
            </div>

            <div class="flex justify-center gap-4 w-full mt-6">
                <div class="flex flex-col items-center">
                    <span class="w-2 h-2 rounded-full bg-green-500 mb-1"></span>
                    <span class="text-[10px] font-bold text-slate-500">Confirmed</span>
                    <span class="text-xs font-bold text-slate-900">92</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="w-2 h-2 rounded-full bg-red-500 mb-1"></span>
                    <span class="text-[10px] font-bold text-slate-500">Cancelled</span>
                    <span class="text-xs font-bold text-slate-900">21</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="w-2 h-2 rounded-full bg-orange-500 mb-1"></span>
                    <span class="text-[10px] font-bold text-slate-500">No Answer</span>
                    <span class="text-xs font-bold text-slate-900">29</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white p-6 rounded-[24px] border border-slate-100 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-900 text-lg">Weekly Earnings</h3>
                <div class="flex items-center gap-2 px-3 py-1 bg-indigo-50 rounded-lg text-indigo-700 text-xs font-bold">
                    <i class="ri-line-chart-line"></i> +15% vs last week
                </div>
            </div>

            <div class="relative h-56 w-full">
                 <div class="absolute inset-0 flex flex-col justify-between text-xs text-slate-300 pointer-events-none">
                    <div class="border-b border-dashed border-slate-100 w-full h-0"></div>
                    <div class="border-b border-dashed border-slate-100 w-full h-0"></div>
                    <div class="border-b border-dashed border-slate-100 w-full h-0"></div>
                    <div class="border-b border-slate-200 w-full h-0"></div>
                </div>

                <svg class="absolute inset-0 w-full h-full overflow-visible" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="agentGradient" x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%" stop-color="#818cf8" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="#818cf8" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path d="M0,220 C100,200 200,100 300,150 C400,180 500,50 600,20 C700,50 800,220 800,220 L0,220 Z" 
                          fill="url(#agentGradient)" />
                    <path d="M0,220 C100,200 200,100 300,150 C400,180 500,50 600,20 C700,50 800,220 800,220" 
                          fill="none" stroke="#6366f1" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    
                    <circle cx="600" cy="20" r="6" fill="white" stroke="#6366f1" stroke-width="4" />
                </svg>
            </div>
            
            <div class="flex justify-between mt-2 text-xs font-bold text-slate-400">
                <span>Sat</span><span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Today</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <h3 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                <i class="ri-list-check-2 text-indigo-500"></i> Tasks Queue
            </h3>
            <button class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition">
                Start Auto-Dialer
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 text-xs uppercase font-bold text-slate-400">
                    <tr>
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Wilaya</th>
                        <th class="px-6 py-4">Attempts</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    
                    <tr class="group hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-slate-800">#ORD-9925</span>
                            <span class="block text-[10px] text-green-600 font-bold">New</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">AM</div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Ahmed M.</p>
                                    <p class="text-[10px] text-slate-400">0550 12 ** **</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-600">16 - Alger</td>
                        <td class="px-6 py-4">
                            <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-1 rounded-md">0 / 3</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition">
                                <button class="w-8 h-8 rounded-full bg-green-50 text-green-600 hover:bg-green-500 hover:text-white flex items-center justify-center transition" title="WhatsApp">
                                    <i class="ri-whatsapp-line"></i>
                                </button>
                                <button class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition" title="Call Now">
                                    <i class="ri-phone-fill"></i>
                                </button>
                                <button class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 transition">
                                    Process
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="group hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <span class="font-mono font-bold text-slate-800">#ORD-9924</span>
                            <span class="block text-[10px] text-orange-500 font-bold">Retry</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center font-bold text-xs">SB</div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Sarah B.</p>
                                    <p class="text-[10px] text-slate-400">0771 99 ** **</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-600">31 - Oran</td>
                        <td class="px-6 py-4">
                            <span class="bg-orange-100 text-orange-600 text-[10px] font-bold px-2 py-1 rounded-md">1 / 3</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition">
                                <button class="w-8 h-8 rounded-full bg-green-50 text-green-600 hover:bg-green-500 hover:text-white flex items-center justify-center transition">
                                    <i class="ri-whatsapp-line"></i>
                                </button>
                                <button class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition">
                                    <i class="ri-phone-fill"></i>
                                </button>
                                <button class="px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 transition">
                                    Process
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection