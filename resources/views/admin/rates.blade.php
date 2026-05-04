@extends('layouts.modern-admin')

@section('content')
<div class="overview-card" style="margin-bottom: 2rem;">
    <div class="overview-header">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">Rate Management</h2>
            <p style="color: var(--text-secondary);">Manage hourly parking rates per vehicle type.</p>
        </div>
        <div class="overview-icon icon-primary" style="background: rgba(34, 211, 238, 0.1); color: var(--secondary-color);">
            <i class="fas fa-tags"></i>
        </div>
    </div>
</div>

@if(session('success'))
<div style="background: rgba(16, 185, 129, 0.1); border-left: 4px solid var(--success-color); color: var(--success-color); padding: 1rem; margin-bottom: 1.5rem; border-radius: 0.125rem;">
    {{ session('success') }}
</div>
@endif

<div class="activity-section" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700;">Parking Rates List</h3>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Vehicle Type</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Hourly Rate (₱)</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Status</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rates as $rate)
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 1rem; font-weight: 600; text-transform: capitalize;">{{ $rate->vehicle_type }}</td>
                    <td style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">₱{{ number_format($rate->hourly_rate, 2) }}</td>
                    <td style="padding: 1rem;">
                        <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; 
                            background: {{ $rate->is_active ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }};
                            color: {{ $rate->is_active ? 'var(--success-color)' : 'var(--danger-color)' }};">
                            {{ $rate->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="padding: 1rem;">
                        <button onclick="openEditModal({{ $rate->id }}, '{{ $rate->vehicle_type }}', {{ $rate->hourly_rate }}, {{ $rate->is_active ? 'true' : 'false' }})" style="background: none; border: none; color: var(--primary-color); cursor: pointer;"><i class="fas fa-edit"></i> Edit</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Rate Modal -->
<div id="editRateModal" class="modal" style="display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(5px); overflow-y: auto;">
    <div style="background: var(--light-surface); margin: 2rem auto; padding: 2rem; border-radius: 0.125rem; width: 400px; box-shadow: var(--shadow-xl);">
        <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 700;">Edit Rate: <span id="editVehicleType" style="text-transform: capitalize;"></span></h3>
        <form id="editRateForm" method="POST">
            @csrf
            @method('PATCH')
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary);">Hourly Rate (₱)</label>
                <input type="number" name="hourly_rate" id="editHourlyRate" step="0.01" min="0" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.125rem; background: var(--light-bg); color: var(--text-primary);">
            </div>
            <div style="margin-bottom: 1.5rem; display: flex; align-items: center;">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="editIsActive" value="1" style="margin-right: 0.5rem; width: 1.2rem; height: 1.2rem;">
                <label for="editIsActive" style="color: var(--text-secondary);">Active</label>
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
    function openEditModal(id, vehicleType, hourlyRate, isActive) {
        document.getElementById('editVehicleType').textContent = vehicleType;
        document.getElementById('editHourlyRate').value = hourlyRate;
        document.getElementById('editIsActive').checked = isActive;
        document.getElementById('editRateForm').action = `/admin/rates/${id}`;
        document.getElementById('editRateModal').style.display = 'block';
    }
    
    function closeEditModal() {
        document.getElementById('editRateModal').style.display = 'none';
    }
</script>
@endsection
