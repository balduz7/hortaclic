@extends('layouts.app')

@section('box-title')
    {{ __('File') . " " . $file->id }}
@endsection

@php
    $cols = [
        "id",
        "filepath",
        "filesize",
        "created_at",
        "updated_at"
    ];
@endphp

@section('box-content')
<div>
        <img class="w-full" src="{{ asset('storage/'.$file->filepath) }}" title="Image preview"/>
        <table class="table">
            <tbody>
                <tr>
                    <td><strong>ID<strong></td>
                    <td>{{ $file->id }}</td>
                </tr>
                <tr>
                    <td><strong>Filepath</strong></td>
                    <td>{{ $file->filepath }}</td>
                </tr>
                <tr>
                    <td><strong>Filesize</strong></td>
                    <td>{{ $file->filesize }}</td>
                </tr>
                <tr>
                    <td><strong>Created</strong></td>
                    <td>{{ $file->created_at }}</td>
                </tr>
                <tr>
                    <td><strong>Updated</strong></td>
                    <td>{{ $file->updated_at }}</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-8">
            <a href="{{ route('files.edit', $file) }}"><x-primary-button >
                {{ __('Edit') }}
            </x-danger-button></a>
            <a href="{{ route('files.delete', $file) }}"> <x-danger-button >
                {{ __('Delete') }}
            </x-danger-button></a>
            <a href="{{ route('files.index') }}"> <x-secondary-button >
                {{ __('Back to list') }}
            </x-secondary-button></a>
        </div>
</div>
@endsection
