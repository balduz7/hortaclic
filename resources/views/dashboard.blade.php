@extends('layouts.app')

@section('box-title')
    {{ __('Dashboard') }}
@endsection

@section('box-content')
<div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="p-8 text-center rounded-lg">
                <div class="flex justify-center space-x-6">
                    <a href="{{ route('posts.index') }}"
                        class="btn bg-white hover:bg-blue-700 text-blue py-3 px-6 rounded-full transition duration-300 ease-in-out">{{ __('Explore Posts') }}</a>
                    <a href="{{ route('places.index') }}"
                        class="btn bg-white hover:bg-blue-700 text-blue py-3 px-6 rounded-full transition duration-300 ease-in-out">{{ __('Discover Places') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

