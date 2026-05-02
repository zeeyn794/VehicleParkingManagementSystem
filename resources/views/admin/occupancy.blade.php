@extends('layouts.modern-admin')

@section('content')
<div class="overview-card" style="margin-bottom: 2rem;">
    <div class="overview-header">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Occupancy Overview</h2>
            <p style="color: var(--text-secondary);">Real-time status and occupancy analytics of your parking facility.</p>
        </div>
        <div class="overview-icon icon-primary">
            <i class="fas fa-chart-pie"></i>
        </div>
    </div>
</div>

<section class="overview-section" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="overview-card" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
        <div class="overview-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <div>
                <div class="overview-title" style="font-size: 1.125rem; font-weight: 600;">Total Slots</div>
                <div class="overview-value" style="font-size: 2rem; font-weight: 700;">{{ $totalSlots }}</div>
                <div class="overview-label" style="color: var(--text-secondary); font-size: 0.875rem;">Capacity</div>
            </div>
            <div class="overview-icon icon-primary" style="background: rgba(245, 48, 3, 0.1); color: var(--primary-color);">
                <i class="fas fa-car"></i>
            </div>
        </div>
    </div>
    <div class="overview-card" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
        <div class="overview-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <div>
                <div class="overview-title" style="font-size: 1.125rem; font-weight: 600;">Occupied</div>
                <div class="overview-value" style="font-size: 2rem; font-weight: 700;">{{ $occupied }}</div>
                <div class="overview-label" style="color: var(--text-secondary); font-size: 0.875rem;">Currently Parked</div>
            </div>
            <div class="overview-icon icon-warning" style="background: rgba(245, 158, 11, 0.1); color: var(--warning-color);">
                <i class="fas fa-parking"></i>
            </div>
        </div>
    </div>
    <div class="overview-card" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
        <div class="overview-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <div>
                <div class="overview-title" style="font-size: 1.125rem; font-weight: 600;">Available</div>
                <div class="overview-value" style="font-size: 2rem; font-weight: 700;">{{ $available }}</div>
                <div class="overview-label" style="color: var(--text-secondary); font-size: 0.875rem;">Open Spaces</div>
            </div>
            <div class="overview-icon icon-success" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</section>

<div class="activity-section" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
    <div class="section-header" style="margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700;">Occupancy Analytics</h3>
    </div>
    <div style="height: 300px; position: relative;">
        <canvas id="occupancyChart"></canvas>
    </div>
</div>

@endsection

@section('extra-js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('occupancyChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Occupied', 'Available'],
                datasets: [{
                    data: [{{ $occupied }}, {{ $available }}],
                    backgroundColor: ['#f53003', '#10b981'],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: "'Instrument Sans', sans-serif",
                                size: 14
                            },
                            color: getComputedStyle(document.documentElement).getPropertyValue('--text-primary')
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endsection