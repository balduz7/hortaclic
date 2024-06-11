@extends('layouts.app')

@section('box-title')
    {{ __('Posts') }}
@endsection

@php
    $cols = [
        "id",
        "body",
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
    @foreach ($posts as $post)
        <li>
            <a href="{{route('posts.show', $post->id)}}">{{$post->id}} - {{$post->post_name}}</a>
        </li>
        @endforeach
    <!-- Pagination -->
    <div class="mt-8">
        {{ $posts->links() }}
    </div>
    <!-- Buttons -->
    <div class="mt-8">
        <a href="{{ route('posts.create') }}"><x-primary-button>
            {{ __('Add new post') }}
        </x-primary-button></a>
        <a href="{{ route('dashboard') }}"><x-secondary-button>
            {{ __('Back to dashboard') }}
        </x-secondary-button></a>
    </div>
@endsection