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
                        <div class="mb-8 border p-3 rounded shadow mx-auto sm:w-full md:w-1/1 lg:w-1/1 xl:w-1/1">
                            <p class="text-lg font-semibold">
                                {{ $post->user ? $post->user->name : __('No information available') }}</p>
                            <a href="{{ route('posts.show', $post) }}">
                                @if ($post->file)
                                <img class="img-fluid mt-2" src="{{ asset('storage/' . $post->file->filepath ) }}"
                                    alt="Imagen" />
                                @endif
                            </a>
                            <br>
                            <div class="flex items-center justify-between mt-2">
                                {{$post->post_name}}
                            </div>
                        </div>
                    @endforeach
                    {{ $posts->links() }}
    <!-- Pagination -->
    <div class="mt-8">
        {{ $posts->links() }}
    </div>
    <!-- Buttons -->
    <div class="mt-8">
    @can('create', App\Models\Post::class)
        <a href="{{ route('posts.create') }}"><x-primary-button>
            {{ __('Add new post') }}
        </x-primary-button></a>
        @endcan
        <a href="{{ route('dashboard') }}"><x-secondary-button>
            {{ __('Back to dashboard') }}
        </x-secondary-button></a>
    </div>
@endsection