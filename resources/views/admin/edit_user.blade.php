@extends('layouts.admin')

@section('content')
<div class="container py-4" style="max-width: 720px;">
    <h3 class="fw-bold mb-3">Edit User</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="card p-3 shadow-sm">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
                <option value="admin"   @selected(old('role', $user->role)==='admin')>Admin</option>
                <option value="coach"   @selected(old('role', $user->role)==='coach')>Coach</option>
                <option value="student" @selected(old('role', $user->role)==='student')>Student</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-dark">Save</button>
            <a class="btn btn-outline-secondary" href="{{ route('admin.users') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
