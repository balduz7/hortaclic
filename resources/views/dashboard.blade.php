@extends('layouts.app')

@section('box-title')
    {{ __('Dashboard') }}
@endsection

@section('box-content')
    <p class="mb-4">{{ __("You're logged in!") }}</p>
    <h2 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">{{ __('Recursos') }}</h2>
 
    <a href="{{ url('/files') }}"><x-primary-button>
        🗄️ {{ __('Arxius') }}
    </x-primary-button></a>
    <a href="{{ url('/posts') }}"><x-primary-button >
        📑 {{ __('Publicacions') }}        
    </x-primary-button></a>

    <a href="{{ url('/places') }}"><x-primary-button >
        📍 {{ __('Llocs') }}
    </x-primary-button></a>
@endsection

