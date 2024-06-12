@extends('layouts.app')

@section('box-title')
    {{ __('Place') . " " . $place->id }}
@endsection

@section('box-content')
    <div>
    <img class="w-full" src="{{ asset('storage/'.$file->filepath) }}" title="Image preview"/>
            <table class="table">
                <tbody>
                    <tr>
                        <td><strong>ID<strong></td>
                        <td>{{ $place->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Name</strong></td>
                        <td>{{ $place->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Description</strong></td>
                        <td>{{ $place->description }}</td>
                    </tr>
                    <tr>
                        <td><strong>Lat</strong></td>
                        <td>{{ $place->latitude }}</td>
                    </tr>
                    <tr>
                        <td><strong>Lng</strong></td>
                        <td>{{ $place->longitude }}</td>
                    </tr>
                    <tr>
                        <td><strong>Visibility</strong></td>
                        <td>{{ $place->visibility_id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Created</strong></td>
                        <td>{{ $place->created_at }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated</strong></td>
                        <td>{{ $place->updated_at }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-8">
            <a href="{{ route('places.edit', $place) }}"><x-primary-button >
                {{ __('Edit') }}
            </x-danger-button></a>
            <a href="{{ route('places.delete', $place) }}"> <x-danger-button >
                {{ __('Delete') }}
            </x-danger-button></a>
            <a href="{{ route('places.index') }}"> <x-secondary-button >
                {{ __('Back to list') }}
            </x-secondary-button></a>
        </div>
        <div class="mt-8">
            <p>{{ $numFavs . " " . __('favs') }}</p>
            @include('partials.buttons-favs')
        </div>
</div>
@endsection
