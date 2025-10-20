@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="mb-4">Datasets</h1>
  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($datasets as $d)
      <div class="p-4 rounded-xl border">
        <h2 class="font-semibold text-lg">{{ $d->name }}</h2>
        <div class="text-sm text-gray-600">{{ strtoupper($d->language) }} • {{ $d->type }} • {{ $d->level }}</div>
        <div class="text-sm mt-1">{{ $d->item_count }} items</div>
      </div>
    @endforeach
  </div>
</div>
@endsection
