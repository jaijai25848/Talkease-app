@extends('layouts.admin')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow rounded-4 p-4">
        <h4 class="fw-bold mb-3">Edit User</h4>
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select id="role" name="role" class="form-select">
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="coach" {{ $user->role === 'coach' ? 'selected' : '' }}>Coach</option>
                    <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                </select>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Update User</button>
            </div>
        </form>
    </div>
</div>
@endsection
