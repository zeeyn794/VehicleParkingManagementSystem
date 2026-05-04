@extends('layouts.modern-admin')

@section('content')
<div class="overview-card" style="margin-bottom: 2rem;">
    <div class="overview-header">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Admin Profile</h2>
            <p style="color: var(--text-secondary);">Manage your account credentials and security settings.</p>
        </div>
        <div class="overview-icon icon-primary">
            <i class="fas fa-user-shield"></i>
        </div>
    </div>
</div>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="activity-section" style="background: var(--light-surface); border-radius: 0.125rem; padding: 2rem; box-shadow: var(--shadow-md);">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;">Account Information</h3>
        
        @if(session('success'))
            <div style="background: rgba(16, 185, 129, 0.1); color: var(--success-color); padding: 1rem; border-radius: 0.125rem; margin-bottom: 1.5rem; border-left: 4px solid var(--success-color);">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 0.125rem; background: var(--light-bg); color: var(--text-primary); font-size: 0.9375rem;" required>
                @error('name') <span style="color: var(--danger-color); font-size: 0.8125rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 0.125rem; background: var(--light-bg); color: var(--text-primary); font-size: 0.9375rem;" required>
                @error('email') <span style="color: var(--danger-color); font-size: 0.8125rem;">{{ $message }}</span> @enderror
            </div>

            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;">Security Update</h3>
            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1.5rem;">Leave password fields blank if you don't want to change it.</p>

            <div style="margin-bottom: 1.5rem; position: relative;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">New Password</label>
                <input type="password" name="password" id="password" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 0.125rem; background: var(--light-bg); color: var(--text-primary); font-size: 0.9375rem; padding-right: 3rem;">
                <i class="fas fa-eye" id="togglePassword" style="position: absolute; right: 1rem; bottom: 1rem; cursor: pointer; color: var(--text-secondary);"></i>
                @error('password') <span style="color: var(--danger-color); font-size: 0.8125rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 2rem; position: relative;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem;">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border-color); border-radius: 0.125rem; background: var(--light-bg); color: var(--text-primary); font-size: 0.9375rem; padding-right: 3rem;">
                <i class="fas fa-eye" id="togglePasswordConfirmation" style="position: absolute; right: 1rem; bottom: 1rem; cursor: pointer; color: var(--text-secondary);"></i>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.025em;">
                Save Profile Changes
            </button>
        </form>
    </div>
</div>
@endsection

@section('extra-js')
<script>
    function setupPasswordToggle(inputId, toggleId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(toggleId);

        toggleIcon.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

    setupPasswordToggle('password', 'togglePassword');
    setupPasswordToggle('password_confirmation', 'togglePasswordConfirmation');
</script>
@endsection
