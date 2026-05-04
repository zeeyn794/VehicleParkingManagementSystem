@extends('layouts.modern-admin')

@section('content')
<div class="overview-card" style="margin-bottom: 2rem;">
    <div class="overview-header">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Parking Logs</h2>
            <p style="color: var(--text-secondary);">Review all parking activities and historical data.</p>
        </div>
        <div class="overview-icon icon-primary">
            <i class="fas fa-history"></i>
        </div>
    </div>
</div>

<div class="activity-section" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700;">Activity History</h3>
        <div class="flex gap-2">
            <form action="{{ route('admin.logs') }}" method="GET" style="display: flex; gap: 0.5rem;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search logs..." style="padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: 0.125rem; font-size: 0.875rem; background: var(--light-bg); color: var(--text-primary);">
                <button type="submit" class="btn" style="padding: 0.5rem 1rem; font-size: 0.875rem; background: white; border: 1px solid var(--border-color); color: #000000; font-weight: 600; width: auto;">Search</button>
            </form>
            <button class="btn btn-primary" onclick="exportLogs()" style="padding: 0.5rem 1rem; font-size: 0.875rem; width: auto;">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">User</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Slot</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Vehicle</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Type</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Entry Time</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Exit Time</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Total Fee</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 1rem;">
                        <span style="font-weight: 500;">{{ $log->user->name ?? 'Unknown User' }}</span>
                    </td>
                    <td style="padding: 1rem; font-weight: 600;">{{ $log->parkingSlot->slot_number ?? 'N/A' }}</td>
                    <td style="padding: 1rem; color: var(--text-secondary);">{{ $log->vehicle->license_plate ?? 'N/A' }}</td>
                    <td style="padding: 1rem; color: var(--text-secondary);"><span style="text-transform: capitalize;">{{ $log->vehicle->type ?? 'Car' }}</span></td>
                    <td style="padding: 1rem; color: var(--text-secondary);">{{ $log->entry_time ? \Carbon\Carbon::parse($log->entry_time)->format('M d, H:i') : '-' }}</td>
                    <td style="padding: 1rem; color: var(--text-secondary);">{{ $log->exit_time ? \Carbon\Carbon::parse($log->exit_time)->format('M d, H:i') : '-' }}</td>
                    <td style="padding: 1rem; color: var(--primary-color); font-weight: 700;">₱{{ number_format($log->total_fee, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 3rem; text-align: center; color: var(--text-secondary);">
                        <i class="fas fa-search" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                        No parking logs found matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 1.5rem;">
        {{ $logs->links() }}
    </div>
</div>
@endsection

@section('extra-js')
<script>
    function exportLogs() {
        const search = document.querySelector('input[name="search"]').value;
        const url = `{{ route('admin.logs.export') }}?search=${encodeURIComponent(search)}`;
        
        Toastify({
            text: "Preparing your CSV export...",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: "var(--primary-color)",
        }).showToast();
        
        window.location.href = url;
    }
</script>
@endsection
