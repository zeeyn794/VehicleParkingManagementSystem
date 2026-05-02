@extends('layouts.modern-admin')

@section('content')
<div class="overview-card" style="margin-bottom: 2rem;">
    <div class="overview-header">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Slot Management</h2>
            <p style="color: var(--text-secondary);">Manage and monitor all parking slots in the facility.</p>
        </div>
        <div class="overview-icon icon-primary">
            <i class="fas fa-parking"></i>
        </div>
    </div>
</div>

<div class="activity-section" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700;">Parking Slots List</h3>
        <button class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
            <i class="fas fa-plus"></i> Add New Slot
        </button>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Slot Number</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Location</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Type</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Status</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Rate/hr</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($slots as $slot)
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 1rem; font-weight: 600;">{{ $slot->slot_number }}</td>
                    <td style="padding: 1rem; color: var(--text-secondary);">{{ $slot->location }}</td>
                    <td style="padding: 1rem; color: var(--text-secondary);">{{ $slot->type ?? 'Standard' }}</td>
                    <td style="padding: 1rem;">
                        <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; 
                            background: {{ $slot->status === 'available' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }};
                            color: {{ $slot->status === 'available' ? 'var(--success-color)' : 'var(--danger-color)' }};">
                            {{ ucfirst($slot->status) }}
                        </span>
                    </td>
                    <td style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">${{ number_format($slot->hourly_rate, 2) }}</td>
                    <td style="padding: 1rem;">
                        <form action="{{ route('admin.slots.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this slot?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: var(--danger-color); cursor: pointer;"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Add Slot Modal -->
<div id="addSlotModal" class="modal" style="display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px);">
    <div style="background: var(--light-surface); margin: 10% auto; padding: 2rem; border-radius: 0.125rem; width: 400px; box-shadow: var(--shadow-xl);">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 700;">Add New Parking Slot</h3>
        <form action="{{ route('admin.slots.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Slot Number</label>
                <input type="text" name="slot_number" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.125rem; background: var(--light-bg); color: var(--text-primary);">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Location</label>
                <input type="text" name="location" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.125rem; background: var(--light-bg); color: var(--text-primary);">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Type</label>
                <select name="type" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.125rem; background: var(--light-bg); color: var(--text-primary);">
                    <option value="Standard">Standard</option>
                    <option value="Premium">Premium</option>
                    <option value="Handicap">Handicap</option>
                    <option value="Electric">Electric</option>
                </select>
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Hourly Rate ($)</label>
                <input type="number" name="hourly_rate" step="0.01" required value="3.00" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.125rem; background: var(--light-bg); color: var(--text-primary);">
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Create Slot</button>
                <button type="button" onclick="closeModal()" class="btn" style="background: var(--border-color); color: var(--text-primary);">Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('extra-js')
<script>
    function openModal() {
        document.getElementById('addSlotModal').style.display = 'block';
    }
    function closeModal() {
        document.getElementById('addSlotModal').style.display = 'none';
    }
    // Update the Add New Slot button to open modal
    document.querySelector('.btn-primary').onclick = openModal;
</script>
@endsection
