@extends('layouts.appstore')

@section('title', 'Developer API - Vendex')

@section('content')
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 font-sans">
        
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">API Tokens</h1>
                <p class="text-slate-500 text-sm mt-2 font-medium">
                    Create and manage access keys for third-party integrations.
                </p>
            </div>
            
            <div class="flex items-center gap-2 px-4 py-2 bg-indigo-50 rounded-xl border border-indigo-100 text-indigo-700 font-bold text-xs">
                <i class="ri-shield-keyhole-line text-lg"></i>
                <span>Secure Zone</span>
            </div>
        </div>

        <div class="relative bg-slate-900 rounded-[24px] shadow-2xl overflow-hidden min-h-[500px]">
             <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-600 rounded-full blur-[120px] opacity-20 pointer-events-none"></div>
             <div class="absolute bottom-0 left-0 w-full h-40 bg-gradient-to-t from-black/60 to-transparent pointer-events-none"></div>

             <div class="relative z-10 p-8 lg:p-12">
                
                <div class="flex items-center gap-5 mb-10">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center text-white text-3xl border border-white/10 backdrop-blur-md shadow-inner">
                        <i class="ri-terminal-box-line"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Manage API Keys</h2>
                        <p class="text-slate-400 text-sm mt-1">Tokens allow third-party services to authenticate with our application on your behalf.</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-slate-200/20 p-6 lg:p-8">
                    @livewire('api.api-token-manager')
                </div>

             </div>
        </div>
    </div>
@endsection