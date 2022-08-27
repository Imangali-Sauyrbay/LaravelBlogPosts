@extends('layouts.main')

@section('main')
    <div style="display: flex; padding: 15px; align-items: center; flex-direction: column; border: 1px solid #ccc; position: relative;">
        <h1>{{ $post->title }}</h1>
        <pre style="width: 100%; word-break: break-all; white-space: pre-wrap; word-wrap: break-word;">{{ $post->content }}</pre>
        <a class="btn btn-outline-success" style="position: absolute; z-index: 99; top: 0; right: 0;" href="{{ route('posts.edit', ['post' => $post->slug]) }}">Edit</a>
    </div>
    <hr>
    <div>
        <h4>Comments</h4>
        @each('posts.components.comment', $post->comments, 'comment', 'posts.components.empty')
    </div>
@endsection
