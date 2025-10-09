@extends('layouts.student')

@section('content')
<div class="container py-4">
    <h2>Edit Profile</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', auth()->user()->first_name) }}">
        </div>
        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', auth()->user()->last_name) }}">
        </div>
        <div class="mb-3">
            <label for="avatar" class="form-label">Profile Picture</label><br>
            @if(auth()->user()->avatar)
                <img src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}" alt="Avatar" class="rounded-circle mb-2" style="width: 80px; height: 80px; object-fit: cover;">
            @else
                <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar" class="rounded-circle mb-2" style="width: 80px; height: 80px;">
            @endif
            <input type="file" name="avatar" id="avatar" class="form-control mt-2" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
