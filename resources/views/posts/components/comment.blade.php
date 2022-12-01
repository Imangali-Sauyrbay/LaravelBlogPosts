<h5>{{ $comment->author->name }}</h5>
<p>{{ $comment->content }}</p>
<span>{{ $comment->created_at->diffForHumans()}}</span>
<hr>
