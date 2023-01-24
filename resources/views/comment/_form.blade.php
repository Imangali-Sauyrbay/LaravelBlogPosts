<div class="my-2">
    @auth
        <form method="POST" action="{{ route('posts.comments.store', ['post' => $post->slug]) }}" class="d-flex flex-column align-items-start justify-content-center">
            @csrf
            <textarea class="form-control" name="comment" cols="5" rows="3" placeholder="Comment">{{ old('comment') }}</textarea>
            <x-errors />
            <button class="btn btn-primary mt-2 flex-grow-0"  type="submit">Add Comment!</button>
        </form>
    @else
        <a href="{{ route('login') }}">Sign-in</a> to post comments!
    @endauth
</div>
<hr>
