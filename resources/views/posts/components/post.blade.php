<div style="display: flex; padding: 10px; margin-bottom: 15px; flex-direction: column; border: 1px solid #ccc">
    <a
    href="{{ route('posts.show', ['post' => $post->slug]) }}"
    class="{{ $post->trashed() ? 'text-muted' : '' }}"
    style="color: black; text-decoration: none;">

        <h4 style="margin: 3px">{{ $post->title }}</h4>

        <hr />

        <pre style="margin: 3px; width: 100%; word-break: break-all; white-space: pre-wrap; word-wrap: break-word;">{{ mb_strcut($post->content, 0, 200) . '...' }}</pre>

        <hr />
    </a>

    <div class="d-flex align-content-center justify-content-between text-muted">
        <x-updated :date="$post->created_at->diffForHumans()" :name="$post->author->name" :user-id="$post->author->id" />
        <span>Comments ({{ $post->comments_count }})</span>
    </div>

    <hr />

    <x-tags :tags="$post->tags" />

    @if (now()->diffInMinutes($post->created_at) < 15)
        <strong>NEW!</strong>
    @endif

    @if ($post->trashed())
        <span style="color:red;">Deleted {{ $post->deleted_at->diffForHumans() }}</span>
    @endif

</div>
