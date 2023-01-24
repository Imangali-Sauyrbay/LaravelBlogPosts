@props([
    'tags'
])

<p {{ $attributes }}>
    @foreach ($tags as $tag)
        <a href="{{ route('posts.tags.index', ['slug' => $tag->slug]) }}" class="badge rounded-pill bg-primary text-decoration-none fs-6">{{ $tag->name }}</a>
    @endforeach
</p>
