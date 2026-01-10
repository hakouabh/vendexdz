<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50">
        
        <div class="mb-6">
            <x-authentication-card-logo />
        </div>

        <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-xl overflow-hidden sm:rounded-2xl border border-gray-100">
            
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold text-gray-900">Welcome back</h2>
                <p class="text-sm text-gray-500 mt-1">Please enter your details to sign in.</p>
            </div>

            <x-validation-errors class="mb-4" />

            @session('status')
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg border border-green-100">
                    {{ $value }}
                </div>
            @endsession

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <x-label for="email" value="{{ __('Email') }}" class="text-gray-700 font-medium mb-1" />
                    <x-input id="email" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@example.com" />
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <x-label for="password" value="{{ __('Password') }}" class="text-gray-700 font-medium" />
                        @if (Route::has('password.request'))
                            <a class="text-xs text-indigo-600 hover:text-indigo-800 font-medium transition" href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>
                    <x-input id="password" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                </div>

                <div class="block">
                    <label for="remember_me" class="flex items-center cursor-pointer group">
                        <x-checkbox id="remember_me" name="remember" class="text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
                        <span class="ms-2 text-sm text-gray-600 group-hover:text-gray-900 transition">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div>
                    <x-button class="w-full justify-center py-3 bg-gray-700 hover:bg-gray-900 active:bg-indigo-800 rounded-xl shadow-md transition ease-in-out duration-150 text-white font-semibold tracking-wide">
                        {{ __('Sign In') }}
                    </x-button>
                </div>
            </form>
            
            @if (Route::has('register'))
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="font-medium text-gray-600 hover:text-indigo-500 transition">
                            Sign up
                        </a>
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>