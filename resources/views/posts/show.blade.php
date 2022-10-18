@extends('layouts.main')

@section('main')
    <div style="display: flex; padding: 15px; align-items: center; flex-direction: column; border: 1px solid #ccc; position: relative;">
        <h1>{{ $post->title }}</h1>
        <pre style="width: 100%; word-break: break-all; white-space: pre-wrap; word-wrap: break-word;">{{ $post->content }}</pre>
    </div>
    @can('update', $post)
    <div class="row mt-2">
        <div class="col col-6 offset-3">
            <a class="btn btn-outline-success w-100" href="{{ route('posts.edit', ['post' => $post->slug]) }}">Edit</a>
        </div>
    </div>
    @endcan
    <hr>
    <div>
        <h4>Comments ({{ $post->comments->count()}})</h4>
        @each('posts.components.comment', $post->comments, 'comment', 'posts.components.empty_comment')
    </div>
@endsection
