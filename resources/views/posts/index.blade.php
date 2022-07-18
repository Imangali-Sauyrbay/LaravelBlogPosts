@extends('layouts.main')

@section('main')
    @each('posts.components.post', $posts, 'post', 'posts.components.empty')
@endsection