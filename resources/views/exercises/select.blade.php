@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <h2>Select Exercise Level & Category</h2>
    <p class="mb-4">Choose your preferred level and practice type below.</p>

    <form action="{{ route('student.practice.select') }}" method="POST" class="mt-4" style="max-width:400px;margin:auto;">
    @csrf
    <div class="mb-3 text-start">
        <label for="level" class="form-label">Level</label>
        <select class="form-select" name="level" id="level" required>
            @foreach($levels as $level)
                <option value="{{ $level }}">{{ ucfirst($level) }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3 text-start">
        <label for="category" class="form-label">Category</label>
        <select class="form-select" name="category" id="category" required>
            @foreach($categories as $category)
                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary w-100" type="submit">Start Practice</button>
</form>

</div>
@endsection
