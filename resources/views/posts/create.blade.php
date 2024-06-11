@extends('layouts.app')

@section('box-title')
    {{ __('Afegir publicació') }}
@endsection

@section('box-content')
    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div>
            <x-input-label for="name" :value="__('Nom')" />
            <x-textarea name="name" id="name" class="block mt-1 w-full" :value="old('name')" />
        </div>
        <div>
            <x-input-label for="content" :value="__('Content')" />
            <x-textarea name="content" id="content" class="block mt-1 w-full" :value="old('content')" />
        </div>
        <div>
            <x-input-label for="upload" :value="__('Upload')" />
            <x-text-input type="file" name="upload" id="upload" class="block mt-1 w-full" :value="old('upload')" />
        </div>
        <div class="mt-8">
            <x-primary-button>
                {{ __('Create') }}
            </x-primary-button>
            <x-secondary-button type="reset">
                {{ __('Reset') }}
            </x-secondary-button>
            <a href="{{ url('posts.index') }}"><x-secondary-button>
                {{ __('Back to list') }}
            </x-secondary-button></a>
        </div>
    </form>
@endsection