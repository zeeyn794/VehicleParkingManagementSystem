@extends('layouts.modern-admin')

@section('content')
<div class="overview-card" style="margin-bottom: 2rem;">
    <div class="overview-header">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Total Earnings</h2>
            <p style="color: var(--text-secondary);">Monitor the financial performance and revenue generated from parking fees.</p>
        </div>
        <div class="overview-icon icon-primary">
            <i class="fas fa-money-bill-wave"></i>
        </div>
    </div>
</div>

<section class="overview-section" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="overview-card" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
        <div class="overview-header" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div class="overview-title" style="font-size: 1.125rem; font-weight: 600;">Today's Earnings</div>
                <div class="overview-value" style="font-size: 2rem; font-weight: 700; color: var(--success-color);">₱{{ number_format($todayEarnings, 2) }}</div>
                <div class="overview-label" style="color: var(--text-secondary); font-size: 0.875rem;">Revenue collected today</div>
            </div>
            <div class="overview-icon icon-success" style="width: 48px; height: 48px; border-radius: 0.125rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>
    </div>
    
    <div class="overview-card" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
        <div class="overview-header" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div class="overview-title" style="font-size: 1.125rem; font-weight: 600;">This Month's Earnings</div>
                <div class="overview-value" style="font-size: 2rem; font-weight: 700; color: var(--primary-color);">₱{{ number_format($monthEarnings, 2) }}</div>
                <div class="overview-label" style="color: var(--text-secondary); font-size: 0.875rem;">Revenue collected this month</div>
            </div>
            <div class="overview-icon icon-primary" style="width: 48px; height: 48px; border-radius: 0.125rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: rgba(245, 48, 3, 0.1); color: var(--primary-color);">
                <i class="fas fa-calendar-alt"></i>
            </div>
        </div>
    </div>

    <div class="overview-card" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
        <div class="overview-header" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <div class="overview-title" style="font-size: 1.125rem; font-weight: 600;">Overall Earnings</div>
                <div class="overview-value" style="font-size: 2rem; font-weight: 700; color: var(--text-primary);">₱{{ number_format($totalEarnings, 2) }}</div>
                <div class="overview-label" style="color: var(--text-secondary); font-size: 0.875rem;">Total revenue to date</div>
            </div>
            <div class="overview-icon" style="width: 48px; height: 48px; border-radius: 0.125rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; background: rgba(100, 116, 139, 0.1); color: var(--text-primary);">
                <i class="fas fa-wallet"></i>
            </div>
        </div>
    </div>
</section>

<div class="activity-section" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700;">Earnings Log</h3>
        <div class="flex gap-2">
            <form action="{{ route('admin.earnings') }}" method="GET" style="display: flex; gap: 0.5rem;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transactions..." style="padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: 0.125rem; font-size: 0.875rem; background: var(--light-bg); color: var(--text-primary);">
                <button type="submit" class="btn" style="padding: 0.5rem 1rem; font-size: 0.875rem; background: white; border: 1px solid var(--border-color); color: #000000; font-weight: 600; width: auto;">Search</button>
            </form>
            <button class="btn btn-primary" onclick="exportEarnings()" style="padding: 0.5rem 1rem; font-size: 0.875rem; width: auto;">
                <i class="fas fa-download"></i> Export
            </button>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Transaction ID</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Date</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">User</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Slot</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Vehicle</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Type</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600; text-align: right;">Amount Collected</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 1rem; font-family: monospace; color: var(--text-secondary);">#TRX-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td style="padding: 1rem; color: var(--text-secondary);">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y H:i') }}</td>
                    <td style="padding: 1rem;">
                        <span style="font-weight: 500;">{{ $transaction->user->name ?? $transaction->vehicle->user->name ?? 'Unknown User' }}</span>
                    </td>
                    <td style="padding: 1rem; font-weight: 600;">{{ $transaction->parkingSlot->slot_number ?? 'N/A' }}</td>
                    <td style="padding: 1rem; color: var(--text-secondary);">{{ $transaction->vehicle->license_plate ?? 'N/A' }}</td>
                    <td style="padding: 1rem; color: var(--text-secondary);"><span style="text-transform: capitalize;">{{ $transaction->vehicle->type ?? 'Car' }}</span></td>
                    <td style="padding: 1rem; color: var(--success-color); font-weight: 700; text-align: right;">₱{{ number_format($transaction->total_fee, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 3rem; text-align: center; color: var(--text-secondary);">
                        <i class="fas fa-receipt" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                        No revenue transactions found matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 1.5rem;">
        {{ $transactions->links() }}
    </div>
</div>
@endsection

@section('extra-js')
<script>
    function exportEarnings() {
        const search = document.querySelector('input[name="search"]').value;
        const url = `{{ route('admin.earnings.export') }}?search=${encodeURIComponent(search)}`;
        
        Toastify({
            text: "Generating earnings report...",
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
