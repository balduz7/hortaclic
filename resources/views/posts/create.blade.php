@extends('layouts.app')
@section("content")
<form id="validacion-posts-create" method="post" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="bg-white p-8 rounded shadow-md">
    @csrf
    
    <label for="body">{{__('Description')}}:</label><br>
    <textarea id="body" name="body" rows="4" cols="50" placeholder="Ingresa tu texto aquí..."></textarea>
    <div id="body-error" class="error-message" style="display:none;color:red;"></div>
    <br>

    <label for="latitude">{{__('Latitude')}}:</label><br>
    <input type="number" id="latitude" name="latitude" step="0.000001" placeholder="Ejemplo: -34.603722">
    <div id="latitude-error" class="error-message" style="display:none;color:red;"></div>
    <br>

    <label for="longitude">{{__('Longitude')}}:</label><br>
    <input type="number" id="longitude" name="longitude" step="0.000001" placeholder="Ejemplo: -58.381592">
    <div id="longitude-error" class="error-message" style="display:none;color:red;"></div>
    <br>

    <label for="visibility_id">{{__('Visibility')}}:</label><br>
    <select id="visibility_id" name="visibility_id" class="form-input w-full border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        <option value="1">Public</option>
        <option value="2">Contacts</option>
        <option value="3">Private</option>
    </select>
    <div id="visibility_id-error" class="error-message" style="display:none;color:red;"></div>
    <br>

    <div class="mb-4">
        <label for="upload" class="block text-gray-700 text-sm font-bold mb-2">{{__('File')}}:</label>
        <input type="file" id="upload" name="upload" class="form-input w-full border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
        <div id="upload-error" class="error-message" style="display:none;color:red;"></div>
    </div>

    <div class="flex justify-between">
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">{{__('Create')}}</button>
        <button type="reset" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">{{__('Reset')}}</button>
    </div>
</form>
<a href="{{ url('/dashboard') }}" class="block mt-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg mx-auto">{{ __('Dashboard') }}</a>
@endsection
