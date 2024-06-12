@extends('layouts.app')

@section('box-title')
    {{ __('Post') . " " . $post->id }}
@endsection

@section('box-content')
<div>
        <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method("PUT")
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
        <div>
            <x-input-label for="visibility" :value="__('Visibility')" />
                <select name="visibility" id="visibility" class="mt-1 p-2 w-full border rounded-md">
                    @foreach($visibilities as $visibility)
                        <option value="{{ $visibility->id }}">{{ __($visibility->name)  }}</option>
                    @endforeach
                </select>
                <span id="error-visibility" class="text-red-500"></span>
        </div>
            <div class="mt-8">
                <x-primary-button>
                    {{ __('Update') }}
                </x-primary-button>
                <x-secondary-button type="reset">
                    {{ __('Reset') }}
                </x-secondary-button>
                <a href="{{ url('posts.index') }}"><x-secondary-button>
                {{ __('Back to list') }}
            </x-secondary-button></a>
            </div>
        </form>
</div>
@endsection