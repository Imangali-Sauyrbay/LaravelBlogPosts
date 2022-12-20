@props([
    'empty' => 'It seems there is no post...',
    'title',
    'subtitle',
    'items'
])

<div class="card mb-4" style="width:18rem">
    <div class="card-body">
        <h4 class="card-title">{{ $title }}</h4>
        <div class="card-text">{{ $subtitle }}</div>
    </div>
    <ul class="list-group list-group-flush">
        @if (is_a($items, 'Illuminate\Support\Collection'))
            @forelse ($items as $item)
                {{ $item }}
            @empty
                <li class="list-group-item">
                    {{ $empty }}
                </li>
            @endforelse
        @else
            {{ $items }}
            @empty(trim($items))
                <li class="list-group-item">
                    {{ $empty }}
                </li>
            @endempty
        @endif
    </ul>
</div>
