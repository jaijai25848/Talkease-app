@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-3">Users</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form class="row g-2 mb-3" method="GET" action="{{ route('admin.users') }}">
        <div class="col-sm-5">
            <input type="text" name="search" class="form-control" placeholder="Search name or email"
                   value="{{ request('search') }}">
        </div>
        <div class="col-sm-3">
            <select name="role" class="form-select">
                <option value="">All roles</option>
                <option value="admin"   @selected(request('role')==='admin')>Admin</option>
                <option value="coach"   @selected(request('role')==='coach')>Coach</option>
                <option value="student" @selected(request('role')==='student')>Student</option>
            </select>
        </div>
        <div class="col-sm-2 d-grid">
            <button class="btn btn-dark">Filter</button>
        </div>
        <div class="col-sm-2 d-grid">
            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Clear</a>
        </div>
    </form>

    <div class="table-responsive bg-white shadow-sm rounded-3">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th width="170">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>{{ trim(($u->first_name ?? '').' '.($u->last_name ?? '')) ?: ($u->name ?? '—') }}</td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge bg-secondary">{{ strtoupper($u->role ?? 'N/A') }}</span></td>
                        <td>
                            <a class="btn btn-sm btn-primary" href="{{ route('admin.users.edit', $u->id) }}">Edit</a>
                            <form class="d-inline" method="POST" action="{{ route('admin.users.destroy', $u->id) }}"
                                  onsubmit="return confirm('Delete this user?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
