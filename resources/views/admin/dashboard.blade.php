@extends('layouts.modern-admin')

@section('extra-css')
<style>
    @media (max-width: 768px) {
        .overview-section { grid-template-columns: 1fr !important; }
    }
</style>
@endsection

@section('content')
<div class="overview-card" style="margin-bottom: 2rem;">
    <div class="overview-header">
        <div>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Welcome back, {{ auth()->user()->name }}</div>
            <div style="color: var(--text-secondary);">Your admin workspace is ready. Monitor parking occupancy, manage slots, review logs, and handle user accounts efficiently.</div>
        </div>
        <div class="overview-icon icon-primary">
            <i class="fas fa-user-shield"></i>
        </div>
    </div>
</div>

<section class="overview-section" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <div class="overview-card" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
        <div class="overview-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <div>
                <div class="overview-title" style="font-size: 1.125rem; font-weight: 600;">Total Parking Slots</div>
                <div class="overview-value" id="stat-total-slots" style="font-size: 2rem; font-weight: 700;">{{ $totalSlots }}</div>
                <div class="overview-label" style="color: var(--text-secondary); font-size: 0.875rem;">Active parking spaces</div>
            </div>
            <div class="overview-icon icon-primary" style="width: 48px; height: 48px; border-radius: 0.125rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: rgba(245, 48, 3, 0.1); color: var(--primary-color);">
                <i class="fas fa-car"></i>
            </div>
        </div>
    </div>
    <div class="overview-card" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
        <div class="overview-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <div>
                <div class="overview-title" style="font-size: 1.125rem; font-weight: 600;">Occupied Slots</div>
                <div class="overview-value" id="stat-occupied" style="font-size: 2rem; font-weight: 700;">{{ $occupied }}</div>
                <div class="overview-label" style="color: var(--text-secondary); font-size: 0.875rem;">Currently in use</div>
            </div>
            <div class="overview-icon icon-warning" style="width: 48px; height: 48px; border-radius: 0.125rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: rgba(245, 158, 11, 0.1); color: var(--warning-color);">
                <i class="fas fa-parking"></i>
            </div>
        </div>
    </div>
    <div class="overview-card" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
        <div class="overview-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <div>
                <div class="overview-title" style="font-size: 1.125rem; font-weight: 600;">Available Slots</div>
                <div class="overview-value" id="stat-available" style="font-size: 2rem; font-weight: 700;">{{ $available }}</div>
                <div class="overview-label" style="color: var(--text-secondary); font-size: 0.875rem;">Ready for parking</div>
            </div>
            <div class="overview-icon icon-success" style="width: 48px; height: 48px; border-radius: 0.125rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    <div class="overview-card" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
        <div class="overview-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <div>
                <div class="overview-title" style="font-size: 1.125rem; font-weight: 600;">Total Earnings</div>
                <div class="overview-value" id="stat-earnings" style="font-size: 2rem; font-weight: 700;">₱{{ number_format($totalEarnings, 2) }}</div>
                <div class="overview-label" style="color: var(--text-secondary); font-size: 0.875rem;">Revenue from parking fees</div>
            </div>
            <div class="overview-icon icon-primary" style="width: 48px; height: 48px; border-radius: 0.125rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>
</section>

<section class="management-section" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <a href="{{ route('admin.slots') }}" class="management-card" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md); text-decoration: none; color: var(--text-primary); border: 1px solid transparent;">
        <div class="management-icon" style="width: 56px; height: 56px; border-radius: 0.125rem; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-bottom: 1rem; background: rgba(245, 48, 3, 0.1); color: var(--primary-color);">
            <i class="fas fa-plus-square"></i>
        </div>
        <div class="management-title" style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">Slot Management</div>
        <div class="management-description" style="color: var(--text-secondary);">Add, edit, or remove parking slots to optimize space utilization.</div>
    </a>
    <a href="{{ route('admin.logs') }}" class="management-card" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md); text-decoration: none; color: var(--text-primary); border: 1px solid transparent;">
        <div class="management-icon" style="width: 56px; height: 56px; border-radius: 0.125rem; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-bottom: 1rem; background: rgba(245, 48, 3, 0.1); color: var(--primary-color);">
            <i class="fas fa-history"></i>
        </div>
        <div class="management-title" style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">Parking Logs</div>
        <div class="management-description" style="color: var(--text-secondary);">Access detailed logs of parking sessions, including entry and exit times.</div>
    </a>
    <a href="{{ route('admin.users') }}" class="management-card" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md); text-decoration: none; color: var(--text-primary); border: 1px solid transparent;">
        <div class="management-icon" style="width: 56px; height: 56px; border-radius: 0.125rem; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-bottom: 1rem; background: rgba(245, 48, 3, 0.1); color: var(--primary-color);">
            <i class="fas fa-user-cog"></i>
        </div>
        <div class="management-title" style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">User Management</div>
        <div class="management-description" style="color: var(--text-secondary);">Manage user registrations, permissions, and account statuses.</div>
    </a>
</section>

<section class="activity-section" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 class="section-title" style="font-size: 1.5rem; font-weight: 700;">Recent Activity</h2>
        <a href="{{ route('admin.logs') }}" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">View All</a>
    </div>
    <div class="activity-list" style="display: flex; flex-direction: column; gap: 1rem;">
        <div class="activity-item" style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: rgba(245, 48, 3, 0.05); border-radius: 0.125rem;">
            <div class="activity-icon" style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: var(--primary-color); color: white;">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="activity-content">
                <div class="activity-text">System is running normally</div>
                <div class="activity-time">Monitor your facility in real-time</div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('extra-js')
<script>
    const LIVE_STATS_URL = '{{ route("admin.live.stats") }}';
    const LIVE_STREAM_URL = '{{ route("admin.live.stream") }}';

    function fetchLiveStats() {
        fetch(LIVE_STATS_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                const el = id => document.getElementById(id);
                if (el('stat-total-slots')) el('stat-total-slots').textContent = data.totalSlots;
                if (el('stat-occupied'))    el('stat-occupied').textContent    = data.occupied;
                if (el('stat-available'))   el('stat-available').textContent   = data.available;
                if (el('stat-earnings'))    el('stat-earnings').textContent    = data.totalEarnings;
            })
            .catch(() => {});
    }

    function applyLiveStats(stats) {
        const el = id => document.getElementById(id);
        if (el('stat-total-slots')) el('stat-total-slots').textContent = stats.totalSlots;
        if (el('stat-occupied'))    el('stat-occupied').textContent    = stats.occupied;
        if (el('stat-available'))   el('stat-available').textContent   = stats.available;
        if (el('stat-earnings'))    el('stat-earnings').textContent    = stats.totalEarnings;
    }

    (function initLive() {
        if (!window.EventSource) {
            fetchLiveStats();
            setInterval(fetchLiveStats, 10000);
            return;
        }

        const es = new EventSource(LIVE_STREAM_URL);

        es.addEventListener('live', (e) => {
            try {
                const payload = JSON.parse(e.data || '{}');
                if (payload.stats) applyLiveStats(payload.stats);
            } catch (_) {}
        });

        es.onerror = () => {
            try { es.close(); } catch (_) {}
            fetchLiveStats();
            setInterval(fetchLiveStats, 10000);
        };
    })();
</script>
@endsection
