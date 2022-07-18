@extends('layouts.main')

@section('main')
    <div style="display: flex; padding: 15px; align-items: center; flex-direction: column; border: 1px solid #ccc">
        <h1>{{ $post->title }}</h1>
        <pre style="width: 100%; word-break: break-all; white-space: pre-wrap; word-wrap: break-word;">{{ $post->content }}</pre>
        <a href="{{ route('posts.edit', ['post' => $post->slug]) }}">Edit</a>
    </div>
@endsection