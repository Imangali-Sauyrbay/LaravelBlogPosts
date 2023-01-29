@forelse ($comments as $comment)
    <div class="row">
        <div class="col-2">
            <a
            class="text-decoration-none text-dark"
            href="{{ route('authors.show', ['author' => $comment->author->id]) }}">
                <x-avatar :author="$comment->author" :size="isset($size) ? $size : 'fluid'"/>
            </a>
        </div>
        <div class="col-10">
            <h5>
                <a
                class="text-decoration-none text-dark"
                href="{{ route('authors.show', ['author' => $comment->author->id]) }}">
                    {{ $comment->author->name }}
                </a>
            </h5>
            <p>{{ $comment->content }}</p>
            <span>{{ $comment->created_at->diffForHumans()}}</span>
            <hr>
        </div>
    </div>
@empty
    <p>No comments!</p>
@endforelse
