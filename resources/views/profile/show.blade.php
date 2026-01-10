@extends('layouts.profile')

@section('title', 'Account Settings - Vendex')

@section('content')
   <div class="px-2 mb-4">
     <button onclick="window.history.back()" 
       class="flex items-center gap-2 px-3 py-2 w-full text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200 group border border-transparent hover:border-indigo-100">
        
        <div class="w-8 h-8 rounded-lg bg-slate-100 group-hover:bg-white flex items-center justify-center transition-colors">
            <i class="ri-arrow-left-line text-lg group-hover:scale-110 transition-transform"></i>
        </div>
        
        <div class="flex flex-col items-start">
            <span class="text-xs font-bold">Go Back</span>
            <span class="text-[9px] text-slate-400 uppercase tracking-widest font-medium">Previous Page</span>
        </div>
     </button>
    </div>
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 font-sans">
        
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Account Settings</h1>
                <p class="text-slate-500 text-sm mt-3 font-medium max-w-2xl leading-relaxed">
                    Manage your personal profile, secure your account, and manage API access.
                </p>
            </div>
            
            <div class="flex items-center gap-4 bg-white px-5 py-3 rounded-2xl border border-slate-100 shadow-sm">
                <div class="flex flex-col items-start">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Status</span>
                    <span class="text-sm font-bold text-emerald-600 flex items-center gap-2 mt-0.5">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        Active
                    </span>
                </div>
            </div>
        </div>

        <div class="space-y-16">

            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    <div class="lg:col-span-1">
                        <h3 class="text-lg font-bold text-slate-900">Profile Information</h3>
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                            Update your account's profile information and email address.
                        </p>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-[24px] shadow-sm border border-slate-100 overflow-hidden relative group hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <div class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-indigo-500 to-purple-600 group-hover:w-2 transition-all duration-300"></div>
                            
                            <div class="p-8">
                                @livewire('profile.update-profile-information-form')
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="hidden sm:block border-t border-slate-200/60"></div>

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()) || Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    <div class="lg:col-span-1">
                        <h3 class="text-lg font-bold text-slate-900">Security Center</h3>
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                            Manage your password and two-factor authentication preferences.
                        </p>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-[24px] shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition duration-300">
                            <div class="bg-slate-50/50 border-b border-slate-100 p-6 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-sm border border-indigo-100">
                                    <i class="ri-shield-keyhole-fill"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Account Protection</h3>
                                    <p class="text-xs text-slate-500 font-medium">Fully Encrypted</p>
                                </div>
                            </div>

                            <div class="p-8 space-y-12">
                                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                                    <div>
                                        <div class="mb-6 flex items-center gap-2 pb-2 border-b border-slate-50">
                                            <span class="text-sm font-bold text-slate-800 uppercase tracking-wide flex items-center gap-2">
                                                <i class="ri-lock-password-line text-indigo-500"></i> Update Password
                                            </span>
                                        </div>
                                        @livewire('profile.update-password-form')
                                    </div>
                                @endif

                                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                                    <div>
                                        <div class="mb-6 flex items-center gap-2 pb-2 border-b border-slate-50">
                                            <span class="text-sm font-bold text-slate-800 uppercase tracking-wide flex items-center gap-2">
                                                <i class="ri-smartphone-line text-indigo-500"></i> Two-Factor Authentication
                                            </span>
                                        </div>
                                        @livewire('profile.two-factor-authentication-form')
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

          

            <div class="hidden sm:block border-t border-slate-200/60"></div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-1">
                    <h3 class="text-lg font-bold text-slate-900">Browser Sessions</h3>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                        View and manage active sessions on other devices. Log out of unrecognized devices immediately.
                    </p>
                </div>
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[24px] shadow-sm border border-slate-100 overflow-hidden p-8 hover:shadow-md transition duration-300">
                        @livewire('profile.logout-other-browser-sessions-form')
                    </div>
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <div class="mt-16">
                    <div class="bg-red-50 rounded-[24px] border border-red-100 overflow-hidden relative group">
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-red-500 group-hover:w-2 transition-all duration-300"></div>
                        <div class="p-8">
                            <div class="flex items-center gap-3 mb-6 text-red-800">
                                <div class="p-2 bg-red-100 rounded-lg">
                                    <i class="ri-alarm-warning-fill text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold">Danger Zone</h3>
                                    <p class="text-xs text-red-600/80 font-medium">Proceed with caution. This action cannot be undone.</p>
                                </div>
                            </div>
                            
                            <div class="bg-white rounded-xl p-6 border border-red-100 shadow-sm">
                                @livewire('profile.delete-user-form')
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection