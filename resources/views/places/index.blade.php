@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Places') }}
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Places') }}
                </h2>
                <a class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center" href="{{ url('/places/create') }}">{{ __('Create') }}</a>
            </div>

            <form action="{{ route('places.index') }}" method="GET" class="mb-4">
                @csrf
                <div class="flex">
                    <input type="text" name="search" placeholder="Buscar en el cuerpo del post" class="form-input flex-grow mr-2" />
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Buscar</button>
                </div>
            </form>

            @foreach ($places as $place)
                @if($place->visibility_id == 1 || ($place->visibility_id == 3 && $place->user->is(auth()->user())))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4 p-6">
                    <div class="mb-4">
                        <p class="text-lg font-semibold">ID: {{ $place->id }}</p>
                        <p class="text-xl">{{ $place->name }}</p>
                        <p>Longitude: {{ $place->longitude }} | Latitude: {{ $place->latitude }}</p>
                    </div>

                    <div class="mb-4">
                        <img class="img-fluid max-w-xs" src="{{ asset("storage/{$place->file->filepath}") }}" />
                    </div>

                    <div class="mb-4">
                        <p>{{ $place->description }}</p>
                    </div>

                    <div class="flex justify-between items-end">
                        <div class="text-left">
                            @can('favorite', $place)
                                <form action="{{ route('places.favorite', ['place' => $place->id]) }}" method="post">
                                    @csrf
                                    <button type="submit" class="flex items-center">
                                        @if($place->favorited_count > 0)
                                            <i class="fa-solid fa-star text-yellow-500 mr-2"></i>
                                        @else
                                            <i class="fa-regular fa-star text-gray-500 mr-2"></i>
                                        @endif
                                        <span>{{ $place->favorited_count }}</span>
                                    </button>
                                </form>
                            @endcan
                        </div>

                        <div class="text-center">
                            @can('delete', $place)
                                <form action="{{ route('places.destroy', ['place' => $place->id]) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded"><i class="fas fa-trash"></i></button>
                                </form>
                            @endcan
                        </div>

                        <div class="text-right">
                            @can('update', $place)
                                <a href="{{ route('places.edit', $place) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">Edit</a>
                            @endcan
                        </div>
                    </div>
                </div>
                @endif
            @endforeach

            {{ $places->links() }}
        </div>
    </div>
@endsection
