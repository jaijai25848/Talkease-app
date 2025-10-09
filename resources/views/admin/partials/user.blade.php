{{-- resources/views/admin/users.blade.php --}}
@extends('layouts.app') {{-- or @extends('layouts.admin') if you have a separate admin layout --}}

@section('content')
<div class="container-fluid py-4" style="background: #f7f8fd; min-height: 100vh;">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">User Management</h3>
        <button class="btn btn-dark">
            <i class="fa fa-plus me-1"></i> Add User
        </button>
    </div>
    <div class="card shadow-sm border-0 rounded-3 p-4 mb-4">
        <div class="d-flex gap-2 mb-3">
            <input type="text" class="form-control" placeholder="Search users..." style="max-width: 250px;">
            <button class="btn btn-outline-secondary">Filter by Role</button>
            <button class="btn btn-outline-dark">Export</button>
        </div>
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Date Joined</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                    <tr>
                        <td class="fw-semibold">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-danger">Admin</span>
                            @elseif($user->role === 'coach')
                                <span class="badge bg-primary">Coach</span>
                            @else
                                <span class="badge bg-secondary">Student</span>
                            @endif
                        </td>
                        <td>
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
