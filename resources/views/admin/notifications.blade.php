@extends('layouts.modern-admin')

@section('content')
<div class="overview-card" style="margin-bottom: 2rem;">
    <div class="overview-header">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">System Notifications</h2>
            <p style="color: var(--text-secondary);">Stay updated with the latest system alerts and activities.</p>
        </div>
        <div class="overview-icon icon-primary">
            <i class="fas fa-bell"></i>
        </div>
    </div>
</div>

<div class="activity-section" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
    <div class="section-header" style="margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700;">Recent Alerts</h3>
    </div>
    
    <div class="notification-list-full">
        @forelse($notifications as $notif)
        <div class="notification-card" style="display: flex; gap: 1.5rem; padding: 1.5rem; border: 1px solid var(--border-color); border-radius: 0.125rem; margin-bottom: 1rem; align-items: center; transition: var(--transition);">
            <div class="notification-icon" style="width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; background: {{ $notif['bg'] }}; color: {{ $notif['color'] }}; flex-shrink: 0;">
                <i class="{{ $notif['icon'] }}"></i>
            </div>
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.25rem;">
                    <h4 style="font-weight: 700; color: var(--text-primary); margin: 0;">{{ $notif['title'] }}</h4>
                    <span style="font-size: 0.8125rem; color: var(--text-secondary);">{{ $notif['time']->diffForHumans() }}</span>
                </div>
                <p style="color: var(--text-secondary); margin: 0; font-size: 0.9375rem;">{{ $notif['message'] }}</p>
            </div>
        </div>
        @empty
        <div style="padding: 4rem; text-align: center; color: var(--text-secondary);">
            <i class="fas fa-bell-slash" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
            <p>No new notifications at this time.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
