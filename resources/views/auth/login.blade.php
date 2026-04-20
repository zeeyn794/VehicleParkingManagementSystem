<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Load your custom login CSS --}}
    @vite(['resources/css/app.css', 'resources/css/login.css'])

    <div class="logo-wrapper">
        <img src="{{ asset('images/parkmasterlogo.png') }}" alt="ParkMaster Logo">
    </div>

    <div class="mb-8 text-center">
        <h2 class="login-title">Welcome Back</h2>
        <p class="login-subtitle">Sign in to manage your vehicles</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="form-label" />
            <x-text-input id="email" class="vms-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="email@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="form-label" />
            <x-text-input id="password" class="vms-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-green-600 hover:text-green-800 underline underline-offset-4" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="flex flex-col items-center space-y-4 pt-4">
            <button type="submit" class="vms-btn-primary">
                {{ __('Log in') }}
            </button>

            <p class="text-sm text-gray-600">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-green-600 font-bold hover:underline">Register here</a>
            </p>
        </div>
    </form>
</x-guest-layout>