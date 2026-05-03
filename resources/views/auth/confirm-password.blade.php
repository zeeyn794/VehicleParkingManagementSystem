<x-guest-layout>
    {{-- Load custom login CSS for consistent styling --}}
    @push('styles')
        @vite(['resources/css/login.css'])
    @endpush

    <div class="logo-wrapper">
        <img src="{{ asset('images/parkmasterlogo.png') }}" alt="ParkMaster Logo">
    </div>

    <div class="mb-8 text-center">
        <h2 class="login-title">Secure Area</h2>
        <p class="login-subtitle">Please confirm your password to continue</p>
    </div>

    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400 text-center px-4">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="form-label" />
            <div class="password-wrapper">
                <x-text-input id="password" class="vms-input pr-10" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password')">
                    <svg id="password-eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.301 8.844 6.942 5.5 12 5.5s8.699 3.344 9.964 6.178a1.012 1.012 0 010 .644C20.699 15.156 17.058 18.5 12 18.5s-8.699-3.344-9.964-6.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <script>
            function togglePasswordVisibility(id) {
                const passwordInput = document.getElementById(id);
                const eyeIcon = document.getElementById(id + '-eye-icon');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.822 7.822L21 21m-2.278-2.278L15.07 15.07m-5.14-5.14a3 3 0 004.243 4.243M9.878 9.878l4.242 4.242" />';
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.644C3.301 8.844 6.942 5.5 12 5.5s8.699 3.344 9.964 6.178a1.012 1.012 0 010 .644C20.699 15.156 17.058 18.5 12 18.5s-8.699-3.344-9.964-6.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
                }
            }
        </script>

        <div class="pt-2">
            <button type="submit" class="vms-btn-primary">
                {{ __('Confirm') }}
            </button>
        </div>
    </form>
</x-guest-layout>
