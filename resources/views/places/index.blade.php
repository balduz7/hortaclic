@extends('layouts.app')

@section('box-title')
        {{ __('Llocs') }}
@endsection
@php
    $cols = [
        "id",
        "name",
        "file_id",
        "latitude",
        "longitude",
        "author.name",
        "visibility.name",
        "created_at",
        "updated_at"
    ];
@endphp
@section('box-content')
    <!-- Results -->
    @foreach ($places as $place)
        <li>
            <a href="{{route('places.show', $place->id)}}">{{$place->id}} - {{$place->name}}</a>
        </li>
        @endforeach
    <!-- Pagination -->
    <div class="mt-8">
        {{ $places->links() }}
    </div>
    <!-- Buttons -->
    <div class="mt-8">
    <a href="{{ url('places/create') }}"><x-primary-button >
            {{ __('Add new place') }}
        </x-primary-button></a>
        <a href="{{ url('dashboard') }}"><x-secondary-button>
            {{ __('Back to dashboard') }}
        </x-secondary-button></a>
    </div>
@endsection
