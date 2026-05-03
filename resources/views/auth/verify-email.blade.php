<x-guest-layout>
    {{-- Load custom login CSS for consistent styling --}}
    @push('styles')
        @vite(['resources/css/login.css'])
    @endpush

    <div class="logo-wrapper">
        <img src="{{ asset('images/parkmasterlogo.png') }}" alt="ParkMaster Logo">
    </div>

    <div class="mb-8 text-center">
        <h2 class="login-title">Verify Email</h2>
        <p class="login-subtitle">One last step to get started</p>
    </div>

    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 text-center px-4">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 font-medium text-sm text-green-600 text-center">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <button type="submit" class="vms-btn-primary">
                    {{ __('Resend Verification Email') }}
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf

            <button type="submit" class="login-link hover:underline">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
