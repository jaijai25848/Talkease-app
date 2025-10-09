@extends('layouts.app')

@section('content')
<div class="container" style="max-width:700px">
    <h3 class="mb-3">Edit Profile</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label class="form-label">First name</label>
            <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Last name</label>
            <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
        </div>

        <hr class="my-4">

        <div class="mb-2">
            <label class="form-label">New password <small class="text-muted">(optional)</small></label>
            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm new password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button class="btn btn-primary">Save changes</button>
        <a href="{{ url()->previous() }}" class="btn btn-light">Cancel</a>
    </form>
</div>
@endsection
