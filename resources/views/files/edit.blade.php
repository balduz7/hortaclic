<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<form method="post" action="{{ route('files.update', $file) }}" enctype="multipart/form-data" class="max-w-md mx-auto mt-6 p-4 bg-white rounded-lg shadow-lg">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label for="upload" class="block text-gray-700 font-bold mb-2">File:</label>
        <input type="file" class="w-full py-2 px-3 border border-gray-300 rounded-lg" name="upload"/>
    </div>
    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg">
        Edit
    </button>
    <a class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg" href="{{ url('/dashboard') }}"><i class="fas fa-arrow-left"></i></a>
</form>
<img class="mt-4 mx-auto max-w-md" src="{{ asset("storage/{$file->filepath}") }}">

