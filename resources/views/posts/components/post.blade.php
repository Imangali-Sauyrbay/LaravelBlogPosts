<a href="{{ route('posts.show', ['post' => $post->slug]) }}" style="color: black; text-decoration: none;">
    <div style="display: flex; padding: 10px; margin-bottom: 15px; flex-direction: column; border: 1px solid #ccc">
        <h4 style="margin: 3px">{{ $post->title }}</h4>
        <pre style="margin: 3px; width: 100%; word-break: break-all; white-space: pre-wrap; word-wrap: break-word;">{{ $post->content }}</pre>
        <p>{{ $post->created_at->diffForHumans() }}</p>
        @if (now()->diffInMinutes($post->created_at) < 15)
            <strong>NEW!</strong>
        @endif
    </div>
</a>