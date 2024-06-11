@extends('layouts.app')

@section('box-title')
    {{ __('Files') }}
@endsection

@php
    $cols = [
        "id",
        "filepath",
        "filesize",
        "created_at",
        "updated_at"
    ];
@endphp

@section('box-content')
    <!-- Results -->
    <ul>
        @foreach ($files as $file)
        <li>
            <a href="{{route('files.show', $file->id)}}">{{$file->id}} - {{$file->filepath}}</a>
        </li>
        @endforeach
    </ul>
    <!-- Buttons -->
    <div class="mt-8">
        <a href="{{ url('files/create') }}"><x-primary-button>
            {{ __('Add new file') }}
        </x-primary-button></a>
       <a href="{{ url('dashboard') }}"><x-secondary-button >
            {{ __('Back to dashboard') }}
        </x-secondary-button></a>
    </div>
@endsection