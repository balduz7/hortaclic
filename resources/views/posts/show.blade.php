@extends('layouts.app')

@section('box-title')
    {{ __('Post') . " " . $post->post_name }}
@endsection


@section('box-content')
<div>
<img class="w-full" src="{{ asset('storage/'.$file->filepath) }}" title="Image preview"/>
        <table class="table">
            <tbody>                
                <tr>
                    <td><strong>ID<strong></td>
                    <td>{{ $post->id }}</td>
                </tr>
                <tr>
                    <td><strong>Nom</strong></td>
                    <td>{{ $post->post_name }}</td>
                </tr>
                <tr>
                    <td><strong>Content</strong></td>
                    <td>{{ $post->content }}</td>
                </tr>
                <tr>
                    <td><strong>Author</strong></td>
                    <td>{{ $post->author_id }}</td>
                </tr>
                <tr>
                    <td><strong>Created</strong></td>
                    <td>{{ $post->created_at }}</td>
                </tr>
                <tr>
                    <td><strong>Updated</strong></td>
                    <td>{{ $post->updated_at }}</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-8">
        <a href="{{ route('posts.edit', $post) }}"><x-primary-button >
                {{ __('Edit') }}
            </x-danger-button></a>
            <a href="{{ route('posts.delete', $post) }}"> <x-danger-button >
                {{ __('Delete') }}
            </x-danger-button></a>
            <a href="{{ route('posts.index') }}"> <x-secondary-button >
                {{ __('Back to list') }}
            </x-secondary-button></a>
        </div>
        <div class="mt-8">
            <p>{{ $numLikes . " " . __('likes') }}</p>
            @include('partials.buttons-likes')
        </div>
</div>
@endsection
