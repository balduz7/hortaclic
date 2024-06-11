
<form method="POST" action="{{ route('posts.like', $post) }}" style="display: inline-block;">
    @csrf
    <x-secondary-button type="submit">➕👍</x-primary-button>
</form>

<form method="POST" action="{{ route('posts.unlike', $post) }}" style="display: inline-block;">
    @csrf
    @method("DELETE")
    <x-secondary-button type="submit">➖👍</x-secondary-button>
</form>
