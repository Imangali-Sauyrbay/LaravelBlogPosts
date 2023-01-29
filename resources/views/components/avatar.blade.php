@props([
    'author',
    'size' => 'fluid'
])

<img
src="{{ $author->image ? $author->image->url() : $author->defaultImageUrl() }}"
class="img-thumbnail avatar avatar-{{ $size }}" alt="avatar">
