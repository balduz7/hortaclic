@extends('layouts.app')

@section("header")
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Places') }}
    </h2>
@endsection

@section("content")
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <a class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center" href="{{ url('/dashboard') }}">{{ __('Dashboard') }}</a>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4 p-6">
            <h3 class="text-lg font-semibold mb-4">Place Details</h3>
            <p class="mb-4"><strong>ID:</strong> {{ $place->id }}</p>
            <p class="mb-4"><strong>Name:</strong> {{ $place->name }}</p>
            <p class="mb-4"><strong>Description:</strong> {{ $place->description }}</p>
            <div class="flex mb-4">
                <a href="{{ route('places.edit', $place) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center mr-4">Edit</a>
                <form action="{{ route('places.destroy', ['place' => $place->id]) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <i class="fa-solid fa-trash mr-2"></i>Delete
                    </button>
                </form>
                <form action="{{ route('places.favorite', ['place' => $place->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        @if ($place->favorited_count > 0)
                            <i class="fa-solid fa-star text-yellow-500 mr-2"></i>
                        @else
                            <i class="fa-regular fa-star mr-2"></i>
                        @endif
                        <span>{{ $place->favorited_count }}</span>
                    </button>
                </form>
            </div>
            <hr class="my-8">
            <h3 class="text-lg font-semibold mb-4">Reviews</h3>
            <form action="{{ route('reviews.store') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="place_id" value="{{ $place->id }}">
                <input type="text" name="message" placeholder="Escribe la review... " class="w-full border-gray-300 rounded-md p-2 mb-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Enviar
                </button>
            </form>
            @if ($place->reviews && $place->reviews->count() > 0)
                <ul class="space-y-4">
                    @foreach ($place->reviews as $review)
                        <li class="border-b border-gray-300 py-4 flex items-start justify-between">
                            <div>
                                <p>{{ $review->message }}</p>
                            </div>
                            <div>
                                @if ($review->user_id == auth()->user()->id)
                                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No hay reviews.</p>
            @endif
        </div>
    </div>
</div>
@endsection
