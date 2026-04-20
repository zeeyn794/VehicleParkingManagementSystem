@vite(['resources/css/app.css', 'resources/css/register.css'])
<x-guest-layout>
      <div class="logo-wrapper">
            <img src="{{ asset('images/parkmasterlogo.png') }}" alt="ParkMaster Logo">
        </div>

        <div class="mb-8 text-center">
            <h2 class="register-title">Create Account</h2>
            <p class="register-subtitle">ParkMaster</p>
        </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Full Name')" class="form-label" />
            <x-text-input id="name" class="vms-input" type="text" name="name" :value="old('name')" required autofocus placeholder="Enter your full name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email Address')" class="form-label" />
            <x-text-input id="email" class="vms-input" type="email" name="email" :value="old('email')" required placeholder="email@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="password" :value="__('Password')" class="form-label" />
                <x-text-input id="password" class="vms-input" type="password" name="password" required placeholder="" />
            </div>
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="form-label" />
                <x-text-input id="password_confirmation" class="vms-input" type="password" name="password_confirmation" required placeholder="" />
            </div>
        </div>

        <div class="flex flex-col items-center space-y-4 pt-4">
            <button type="submit" class="vms-btn-primary">
                {{ __('Create Account') }}
            </button>

            <a class="login-link" href="{{ route('login') }}">
                {{ __('Already have an account? Sign in') }}
            </a>
        </div>
    </form>
</x-guest-layout>