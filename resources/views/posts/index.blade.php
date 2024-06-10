@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Posts') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Posts') }}
                </h2>
                <a class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center" href="{{ url('/posts/create') }}">{{ __('Create') }}</a>
            </div>

            <form action="{{ route('posts.index') }}" method="GET" class="mb-4">
                @csrf
                <div class="flex">
                    <input type="text" name="search" placeholder="Buscar en el cuerpo del post" class="form-input flex-grow mr-2" />
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Buscar</button>
                </div>
            </form>

            @foreach ($posts as $post)
                @if($post->visibility_id == 1 || ($post->visibility_id == 3 && $post->user->is(auth()->user())))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 p-6">
                    <div class="mb-4">
                        <p class="text-lg font-semibold">ID: {{ $post->id }}</p>
                        <p class="text-xl">{{ $post->body }}</p>
                        <p>Longitude: {{ $post->longitude }} | Latitude: {{ $post->latitude }}</p>
                    </div>

                    <div class="mb-4">
                        <img class="img-fluid max-w-xs" src="{{ asset("storage/{$post->file->filepath}") }}" />
                    </div>

                    <div class="flex justify-between items-end">
                        <div class="text-left">
                            @can('like', $post)
                                <form action="{{ route('posts.like', ['post' => $post->id]) }}" method="post">
                                    @csrf
                                    <button type="submit" class="flex items-center">
                                        @if($post->isLiked)
                                            <button type="submit"><i class="fa-solid fa-heart" style="color: #ff0000;"></i> {{$post->liked_count}}</button>
                                        @else
                                            <button type="submit"> <i class="fa-regular fa-heart"></i> {{$post->liked_count}}</button>
                                        @endif
                                    </button>
                                </form>
                            @endcan
                        </div>

                        <div class="text-center">
                            @can('delete', $post)
                                <form action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded"><i class="fas fa-trash"></i></button>
                                </form>
                            @endcan
                        </div>

                        <div class="text-right">
                            @can('update', $post)
                                <a href="{{ route('posts.edit', $post) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">Edit</a>
                            @endcan
                        </div>
                        <div class="text-right">
                             @can('view',$post)                             
                                <td class="px-6 py-4 whitespace-nowrap"><a href="{{ route('posts.show', ['post' => $post->id]) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">Ver</a></td>
                              @endcan
                        </div>
                    </div>
                </div>
                @endif
            @endforeach

            {{ $posts->links() }}
        </div>
    </div>
@endsection


