@extends('layouts.modern-admin')

@section('content')
<div class="overview-card" style="margin-bottom: 2rem;">
    <div class="overview-header">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">User Management</h2>
            <p style="color: var(--text-secondary);">Manage user accounts and their roles in the system.</p>
        </div>
        <div class="overview-icon icon-primary">
            <i class="fas fa-users"></i>
        </div>
    </div>
</div>

<div class="activity-section" style="background: var(--light-surface); border-radius: 0.125rem; padding: 1.5rem; box-shadow: var(--shadow-md);">
    <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.25rem; font-weight: 700;">System Users</h3>
        <form action="{{ route('admin.users') }}" method="GET" style="display: flex; gap: 0.5rem;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." style="padding: 0.5rem 1rem; border: 1px solid var(--border-color); border-radius: 0.125rem; font-size: 0.875rem; background: var(--light-bg); color: var(--text-primary);">
            <button type="submit" class="btn" style="padding: 0.5rem 1rem; font-size: 0.875rem; background: white; border: 1px solid var(--border-color); color: #000000; font-weight: 600; width: auto;">Search</button>
        </form>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); text-align: left;">
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Name</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Email</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Role</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Joined Date</th>
                    <th style="padding: 1rem; color: var(--text-secondary); font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 1rem;">
                        <span style="font-weight: 600;">{{ $user->name }}</span>
                    </td>
                    <td style="padding: 1rem; color: var(--text-secondary);">{{ $user->email }}</td>
                    <td style="padding: 1rem;">
                        <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; 
                            background: rgba(34, 211, 238, 0.1); color: var(--secondary-color);">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td style="padding: 1rem; color: var(--text-secondary);">{{ $user->created_at->format('M d, Y') }}</td>
                    <td style="padding: 1rem;">
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
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
@endsection
