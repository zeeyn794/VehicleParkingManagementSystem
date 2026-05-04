@extends('layouts.app')

@section('title', 'Parking History')

@section('extra-head')
<style>
    :root {
        --radius-lg: 12px;
        --radius-md: 8px;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
    }

    .page-header { margin-bottom: 1.75rem; }
    .page-title  { font-size: 1.5rem; font-weight: 700; color: var(--gray-900); margin-bottom: 0.25rem; }
    .page-subtitle { font-size: 0.875rem; color: var(--gray-400); }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    .summary-card {
        background: #fff;
        border: 1px solid var(--gray-100);
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.5rem;
        box-shadow: var(--shadow-sm);
    }
    .summary-label { font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: var(--gray-400); margin-bottom: 0.4rem; }
    .summary-value { font-size: 1.75rem; font-weight: 700; color: var(--gray-900); }
    .summary-value.accent { color: var(--blue-600); }

    .toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
        margin-bottom: 1.25rem;
    }
    .search-wrap {
        display: flex;
        flex: 1;
        min-width: 200px;
        max-width: 340px;
        border: 1px solid var(--gray-100);
        border-radius: var(--radius-md);
        background: #fff;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }
    .search-wrap input {
        flex: 1;
        padding: 0.6rem 0.75rem;
        border: none;
        outline: none;
        font-size: 0.875rem;
        color: var(--gray-900);
        background: transparent;
    }
    .search-wrap button {
        padding: 0 0.9rem;
        background: var(--blue-600);
        color: #fff;
        border: none;
        cursor: pointer;
        font-size: 0.85rem;
    }
    .filter-group { display: flex; gap: 0.5rem; flex-wrap: wrap; }
    .filter-btn {
        padding: 0.45rem 1rem;
        border-radius: 999px;
        border: 1px solid var(--gray-100);
        background: #fff;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--gray-600);
        text-decoration: none;
        transition: .15s;
        cursor: pointer;
    }
    .filter-btn:hover  { border-color: var(--blue-400); color: var(--blue-600); }
    .filter-btn.active { background: var(--blue-600); border-color: var(--blue-600); color: #fff; }

    .table-card {
        background: #fff;
        border: 1px solid var(--gray-100);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    thead tr { border-bottom: 1px solid var(--gray-100); }
    th {
        padding: 0.9rem 1rem;
        text-align: left;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: var(--gray-400);
        white-space: nowrap;
    }
    tbody tr { border-bottom: 1px solid var(--gray-100); transition: background .15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--gray-50); }
    td { padding: 0.85rem 1rem; font-size: 0.875rem; color: var(--gray-900); vertical-align: middle; white-space: nowrap; }
    .td-muted { color: var(--gray-400); }
    .td-mono { font-family: 'DM Mono', monospace; font-size: 0.8rem; }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 0.25rem 0.65rem;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .badge-active    { background: #E6F9F1; color: #0D7A50; }
    .badge-completed { background: var(--blue-50); color: var(--blue-600); }

    .amount-cell { font-weight: 700; color: #0D7A50; }

    .empty-state { text-align: center; padding: 4rem 1rem; color: var(--gray-400); }
    .empty-state i { font-size: 2.5rem; margin-bottom: 1rem; display: block; }
    .empty-state p { font-size: 0.9rem; }

    .pagination-wrap { padding: 1rem 1.25rem; border-top: 1px solid var(--gray-100); }
    .pagination-wrap nav { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
    .pagination-wrap .page-link,
    .pagination-wrap span[aria-current] span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        padding: 0 0.5rem;
        border-radius: var(--radius-md);
        font-size: 0.8rem;
        font-weight: 500;
        border: 1px solid var(--gray-100);
        background: #fff;
        color: var(--gray-600);
        text-decoration: none;
        transition: .15s;
    }
    .pagination-wrap .page-link:hover { border-color: var(--blue-400); color: var(--blue-600); }
    .pagination-wrap span[aria-current] span { background: var(--blue-600); border-color: var(--blue-600); color: #fff; }
</style>
@endsection

@section('content')

<header class="page-header">
    <h1 class="page-title">Parking History</h1>
    <p class="page-subtitle">A full record of your parking sessions and fees paid.</p>
</header>

<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-label">Total Sessions</div>
        <div class="summary-value">{{ $totalSessions }}</div>
    </div>
    <div class="summary-card">
        <div class="summary-label">Total Spent</div>
        <div class="summary-value accent">₱{{ number_format($totalSpent, 2) }}</div>
    </div>
    <div class="summary-card">
        <div class="summary-label">Showing</div>
        <div class="summary-value">{{ $transactions->total() }}</div>
    </div>
</div>

<form method="GET" action="{{ route('user.history') }}" class="toolbar">
    <div class="search-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search slot or plate…">
        <button type="submit"><i class="fas fa-search"></i></button>
    </div>

    @if(request('filter') && request('filter') !== 'all')
        <input type="hidden" name="filter" value="{{ request('filter') }}">
    @endif
</form>

<div class="filter-group" style="margin-bottom: 1.25rem;">
    @foreach(['all' => 'All Time', 'today' => 'Today', 'week' => 'This Week', 'month' => 'This Month'] as $key => $label)
        <a href="{{ route('user.history', array_merge(request()->only('search'), ['filter' => $key])) }}"
           class="filter-btn {{ (request('filter', 'all') === $key) ? 'active' : '' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="table-card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Slot</th>
                    <th>Vehicle</th>
                    <th>Entry</th>
                    <th>Exit</th>
                    <th>Duration</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                @php
                    $isActive    = $tx->exit_time && $tx->exit_time->isFuture();
                    $entry       = $tx->entry_time;
                    $exit        = $tx->exit_time;
                    $durationMin = $entry && $exit ? $entry->diffInMinutes($exit) : null;
                    $durationStr = $durationMin !== null
                        ? (floor($durationMin / 60) > 0 ? floor($durationMin / 60).'h ' : '') . ($durationMin % 60).'m'
                        : '—';
                @endphp
                <tr>
                    <td class="td-muted td-mono">#{{ str_pad($tx->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td><strong>{{ $tx->parkingSlot->slot_number ?? '—' }}</strong>
                        @if($tx->parkingSlot?->location)
                            <br><span class="td-muted" style="font-size:.75rem;">{{ $tx->parkingSlot->location }}</span>
                        @endif
                    </td>
                    <td>
                        {{ $tx->vehicle->license_plate ?? '—' }}
                        @if($tx->vehicle?->type)
                            <br><span class="td-muted" style="font-size:.75rem;">{{ $tx->vehicle->type }}</span>
                        @endif
                    </td>
                    <td class="td-muted">
                        {{ $entry ? $entry->format('M d, Y') : '—' }}<br>
                        <span style="font-size:.75rem;">{{ $entry ? $entry->format('h:i A') : '' }}</span>
                    </td>
                    <td class="td-muted">
                        {{ ($exit && !$isActive) ? $exit->format('M d, Y') : '—' }}<br>
                        <span style="font-size:.75rem;">{{ ($exit && !$isActive) ? $exit->format('h:i A') : '' }}</span>
                    </td>
                    <td class="td-mono">{{ $durationStr }}</td>
                    <td class="amount-cell">
                        @if($tx->total_fee > 0)
                            ₱{{ number_format($tx->total_fee, 2) }}
                        @else
                            <span class="td-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($isActive)
                            <span class="badge badge-active"><i class="fas fa-circle" style="font-size:.5rem;"></i> Active</span>
                        @else
                            <span class="badge badge-completed"><i class="fas fa-check"></i> Completed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-receipt"></i>
                            <p>No parking sessions found{{ request('search') ? ' for "'.request('search').'"' : '' }}.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
        <div class="pagination-wrap">
            {{ $transactions->links() }}
        </div>
    @endif
</div>

@endsection
