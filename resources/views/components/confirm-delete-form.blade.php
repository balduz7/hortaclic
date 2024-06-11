<p>{{ __("Are you sure you want to delete this element?") }}</p>
<form method="POST" action="{{ route($parentRoute . '.destroy', $model) }}">
    @csrf
    @method("DELETE")
    <div class="mt-4">
        <x-danger-button>
            {{ __('Confirm delete') }}
        </x-danger-button>
        <a href="{{ url($parentRoute) }}"><x-secondary-button >
            {{ __('Back to list') }}
        </x-secondary-button></a>     
    </div>
</form>