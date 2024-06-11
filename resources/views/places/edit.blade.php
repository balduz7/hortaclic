@extends('layouts.app')

@section('box-title')
    {{ $place->name }}
@endsection

@section('box-content')
<div>
        <img class="w-full" src="{{ asset('storage/'.$file->filepath) }}" title="Image preview"/>
        <form method="POST" action="{{ route('places.update', $place) }}" enctype="multipart/form-data">
            @csrf
            @method("PUT")
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input type="text" name="name" id="name" class="block mt-1 w-full" :value="old('name')" />
        </div>
        <div>
            <x-input-label for="description" :value="__('Description')" />
            <x-textarea name="description" id="description" class="block mt-1 w-full" :value="old('description')" />
        </div>
        <div>
            <x-input-label for="address" :value="__('address')" />
            <x-textarea name="address" id="address" class="block mt-1 w-full" :value="old('address')" />
        </div>
        <div>
            <x-input-label for="upload" :value="__('Upload')" />
            <x-text-input type="file" name="upload" id="upload" class="block mt-1 w-full" :value="old('upload')" />
        </div>
        <div>
            <x-input-label for="latitude" :value="__('Latitude')" />
            <x-text-input type="text" name="latitude" id="latitude" class="block mt-1 w-full" value="41.2310371" />
        </div>
        <div>
            <x-input-label for="longitude" :value="__('Longitude')" />
            <x-text-input type="text" name="longitude" id="longitude" class="block mt-1 w-full" value="1.7282036" />
        </div>
            <div class="mt-8">
                <x-primary-button>
                    {{ __('Update') }}
                </x-primary-button>
                <x-secondary-button type="reset">
                    {{ __('Reset') }}
                </x-secondary-button>
                <a href="{{ url('places.index') }}"><x-secondary-button>
                {{ __('Back to list') }}
            </x-secondary-button></a>
            </div>
        </form>
</div>
@endsection