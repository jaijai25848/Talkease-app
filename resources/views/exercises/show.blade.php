@extends('layouts.student')

@section('content')
<div class="container py-4">
    <div class="card p-4">
        <div class="mb-2">
            <span class="badge bg-success">{{ ucfirst($exercise->level) }}</span>
            <span class="badge bg-secondary">{{ ucfirst($exercise->category ?? 'word') }}</span>
        </div>
        <h5 class="fw-bold mb-3">{{ $exercise->title ?? $exercise->word }}</h5>
        <p class="mb-4">{{ $exercise->description ?? '' }}</p>
        <a href="{{ route('exercises.select') }}" class="btn btn-outline-primary">Practice This Type</a>
    </div>
    <div class="mt-3">
        <a href="{{ route('exercises.index') }}" class="btn btn-link">&larr; Back to list</a>
    </div>
</div>
@endsection
