@extends('layouts.app')

@section('content')
    <section class="max-w-4xl mx-auto min-h-screen p-8">
        <h1 class="mb-4 text-2xl text-stone-600 dark:text-stone-500 tracking-wide font-serif">Search and/or filter by Tags</h1>

        @livewire('search')
    </section>
@endsection
