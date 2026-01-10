<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50">
        
        <div class="mb-2">
            <x-authentication-card-logo />
        </div>

        <div class="w-full sm:max-w-md px-8 py-5 bg-white shadow-xl overflow-hidden sm:rounded-2xl border border-gray-100">
            
            <div class="mb-8 text-center">
                <h2 class="text-2xl font-bold text-gray-900">Create an account</h2>
                <p class="text-sm text-gray-500 mt-1">Scale your business with Vendex.</p>
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <x-label for="name" value="{{ __('Name-StoreName') }}" class="text-gray-700 font-medium mb-1" />
                    <x-input id="name" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Baouni-store" />
                </div>

                <div>
                    <x-label for="email" value="{{ __('Email') }}" class="text-gray-700 font-medium mb-1" />
                    <x-input id="email" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@vendexdz.com" />
                </div>

                <div>
                    <x-label for="password" value="{{ __('Password') }}" class="text-gray-700 font-medium mb-1" />
                    <x-input id="password" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                </div>

                <div>
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-gray-700 font-medium mb-1" />
                    <x-input id="password_confirmation" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm py-2.5" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mt-4">
                        <x-label for="terms">
                            <div class="flex items-center">
                                <x-checkbox name="terms" id="terms" required class="text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />

                                <div class="ms-2 text-sm text-gray-600">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-indigo-600 hover:text-indigo-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-indigo-600 hover:text-indigo-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </div>
                            </div>
                        </x-label>
                    </div>
                @endif

                <div class="pt-2">
                    <x-button class="w-full justify-center py-3 bg-gray-700 hover:bg-gray-900 active:bg-indigo-800 rounded-xl shadow-md transition ease-in-out duration-150 text-white font-semibold tracking-wide">
                        {{ __('Create Account') }}
                    </x-button>
                </div>

                <div class="text-center mt-4">
                    <a class="text-sm text-gray-500 hover:text-gray-900 underline transition rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('Already registered? Sign in') }}
                    </a>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>