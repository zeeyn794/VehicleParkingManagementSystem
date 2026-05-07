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
        <div class="flex gap-2">
            <form action="{{ route('admin.slots') }}" method="GET" style="display: flex; gap: 0.5rem;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search slots..." style="padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: 0.125rem; font-size: 0.875rem; background: var(--light-bg); color: var(--text-primary);">
                <button type="submit" class="btn" style="padding: 0.5rem 1rem; font-size: 0.875rem; background: white; border: 1px solid var(--border-color); color: #000000; font-weight: 600; width: auto;">Search</button>
            </form>
            <button class="btn btn-primary" onclick="openModal()" style="padding: 0.5rem 1rem; font-size: 0.875rem; width: auto;">
                <i class="fas fa-plus"></i> Add New Slot
            </button>
        </div>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Slot Number</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Location</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Type</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Status</th>

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
                        @php
                            $status = $slot->status ?? 'available';
                            $badgeBg = match($status) {
                                'available'   => 'rgba(16, 185, 129, 0.1)',
                                'occupied'    => 'rgba(239, 68, 68, 0.1)',
                                'maintenance' => 'rgba(245, 158, 11, 0.1)',
                                default       => 'rgba(100, 116, 139, 0.1)',
                            };
                            $badgeColor = match($status) {
                                'available'   => 'var(--success-color)',
                                'occupied'    => 'var(--danger-color)',
                                'maintenance' => 'var(--warning-color)',
                                default       => 'var(--text-secondary)',
                            };
                        @endphp
                        <span id="slot-badge-{{ $slot->id }}" data-slot-id="{{ $slot->id }}" style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;
                            background: {{ $badgeBg }}; color: {{ $badgeColor }}; display: inline-flex; align-items: center; gap: 0.375rem;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor; display: inline-block;"></span>
                            {{ ucfirst($status) }}
                        </span>
                    </td>

                    <td style="padding: 1rem;">
                        <div style="display: flex; gap: 0.5rem;">
                            <button type="button" onclick="openEditModal({{ $slot->id }}, '{{ $slot->slot_number }}', '{{ $slot->location }}', '{{ $slot->type }}', '{{ $slot->status }}')" style="background: none; border: none; color: var(--primary-color); cursor: pointer;"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.slots.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this slot?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: var(--danger-color); cursor: pointer;"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="addSlotModal" class="modal" style="display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); overflow-y: auto;">
    <div style="background: var(--light-surface); margin: 2rem auto; padding: 2rem; border-radius: 0.125rem; width: 400px; box-shadow: var(--shadow-xl);">
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

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Create Slot</button>
                <button type="button" onclick="closeModal()" class="btn" style="background: var(--border-color); color: var(--text-primary);">Cancel</button>
            </div>
        </form>
    </div>
</div>

<div id="editSlotModal" class="modal" style="display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); overflow-y: auto;">
    <div style="background: var(--light-surface); margin: 2rem auto; padding: 2rem; border-radius: 0.125rem; width: 400px; box-shadow: var(--shadow-xl);">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 700;">Edit Parking Slot</h3>
        <form id="editSlotForm" method="POST">
            @csrf
            @method('PATCH')
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Slot Number</label>
                <input type="text" name="slot_number" id="editSlotNumber" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.125rem; background: var(--light-bg); color: var(--text-primary);">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Location</label>
                <input type="text" name="location" id="editLocation" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.125rem; background: var(--light-bg); color: var(--text-primary);">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Type</label>
                <select name="type" id="editType" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.125rem; background: var(--light-bg); color: var(--text-primary);">
                    <option value="Standard">Standard</option>
                    <option value="Premium">Premium</option>
                    <option value="Handicap">Handicap</option>
                    <option value="Electric">Electric</option>
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Status</label>
                <select name="status" id="editStatus" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.125rem; background: var(--light-bg); color: var(--text-primary);">
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" onclick="closeEditModal()" class="btn" style="background: var(--border-color); color: var(--text-primary);">Cancel</button>
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
    
    function openEditModal(id, slotNumber, location, type, status) {
        document.getElementById('editSlotNumber').value = slotNumber;
        document.getElementById('editLocation').value = location;
        document.getElementById('editType').value = type || 'Standard';
        document.getElementById('editStatus').value = status;
        
        document.getElementById('editSlotForm').action = `/admin/slots/${id}`;
        document.getElementById('editSlotModal').style.display = 'block';
    }
    
    function closeEditModal() {
        document.getElementById('editSlotModal').style.display = 'none';
    }

    document.querySelector('.btn-primary').onclick = openModal;

    const LIVE_SLOTS_URL = '{{ route("admin.live.slots") }}';

    const STATUS_STYLES = {
        available:   { bg: 'rgba(16, 185, 129, 0.1)', color: 'var(--success-color)' },
        occupied:    { bg: 'rgba(239, 68, 68, 0.1)',  color: 'var(--danger-color)' },
        maintenance: { bg: 'rgba(245, 158, 11, 0.1)', color: 'var(--warning-color)' },
    };

    function fetchLiveSlots() {
        fetch(LIVE_SLOTS_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(data => {
                data.slots.forEach(slot => {
                    const badge = document.getElementById('slot-badge-' + slot.id);
                    if (!badge) return;
                    const style = STATUS_STYLES[slot.status] || STATUS_STYLES.available;
                    badge.style.background = style.bg;
                    badge.style.color = style.color;
                    badge.lastChild.textContent = ' ' + slot.status.charAt(0).toUpperCase() + slot.status.slice(1);
                });
            })
            .catch(() => {});
    }

    setInterval(fetchLiveSlots, 10000);
</script>
@endsection
