<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    .avatar {
        width: 100px;
        height: 100px;
        max-width: 100px;
        max-height: 100px;
        border: 2px #ccc solid;
        border-radius: 50%;
    }

    .comment-container {
        display: flex;
    }

    .comment-container .avatar {
        width: 16%;
        flex: 0 0 auto;
    }

    .comment-container .comment {
        width: 80%;
        flex: 0 0 auto;

        margin-left: 4%;
        display: flex;
        flex-direction: column;
    }

</style>

<p>Hi {{ $comment->commentable->name }}</p>

<p>
    Someone has commented on your <a href="{{ route('authors.show', ['author' => $comment->commentable->id]) }}">
        profile
    </a>
</p>

<hr/>

<div class="comment-container">
    <img class="avatar" src="{{ $message->embed($comment->author->image ? $comment->author->image->absolutePath() : $comment->author->defaultImagePath()) }}"/>

    <div class="comment">
        <a href="{{ route('authors.show', ['author' => $comment->author->id]) }}">
            {{ $comment->author->name }}
        </a> <span>said:</span>
        <p>
            "{{ $comment->content }}"
        </p>
    </div>
</div>


