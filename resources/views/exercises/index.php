@extends('layouts.student')

@section('content')
<div class="container py-4">
    <h4>All Exercises</h4>
    @foreach($exercises as $level => $items)
        <h5 class="mt-4 text-primary">{{ ucfirst($level) }}</h5>
        <ul class="list-group mb-3">
        @foreach($items as $exercise)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $exercise->title ?? $exercise->word }}</strong>
                    <span class="badge bg-secondary ms-2">{{ ucfirst($exercise->category ?? 'word') }}</span>
                </div>
                <a href="{{ route('exercises.show', $exercise->id) }}" class="btn btn-sm btn-outline-primary">View</a>
            </li>
        @endforeach
        </ul>
    @endforeach
</div>
@endsection
