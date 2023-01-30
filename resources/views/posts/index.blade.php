@extends('layouts.main')

@section('main')
    <div class="row">
        <div class="col-8">
            @isset($tag_name)
                <h4>{{ $tag_name }}</h4>
            @endisset

            {!! $links !!}
            @each('posts.components.post', $posts, 'post', 'posts.components.empty')
            {!! $links !!}
        </div>

        <div class="col-4">
            @include('posts.components._activity')
        </div>
    </div>
@endsection
