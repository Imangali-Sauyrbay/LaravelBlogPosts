<span class="text-muted">
    {{ empty((string)$slot) ? 'Added' : $slot}} {{ $date }}
    <br>
    @isset($name)
        @if (isset($userId))
            By <a class="text-muted text-decoration-none d-inline" href="{{ route('authors.show', ['author' => $userId]) }}">{{ $name }}</a>
        @else
            By {{ $name }}
        @endif
    @endisset
</span>
