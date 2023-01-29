@extends('layouts.main')

@section('main')
   <div class="row">
        <div class="col-8">
            <div style="display: flex; padding: 15px; align-items: center; flex-direction: column; border: 1px solid #ccc; position: relative;">

                @if (isset($post->image))
                    <div style="background-image: url({{ $post->image->url() }}); min-height: 20rem; color: white; text-align:center; background-attachment: fixed; background-size:contain; background-repeat: no-repeat; padding: 10px; margin-bottom: 20px">
                        <h1 style="padding-top: 2rem; text-shadow: 1px 2px black;">{{ $post->title }}</h1>
                    </div>
                @else
                    <h1>{{ $post->title }}</h1>
                @endif

                <pre style="width: 100%; word-break: break-all; white-space: pre-wrap; word-wrap: break-word;">{{ $post->content }}</pre>
            </div>

            <x-tags :tags="$post->tags" class="mt-3"/>

            @can('update', $post)
                <div class="row mt-2">
                    <div class="col col-6 offset-3">
                        <a class="btn btn-outline-success w-100" href="{{ route('posts.edit', ['post' => $post->slug]) }}">Edit</a>
                    </div>
                </div>
            @endcan

            <hr>
            <p>currently reading by {{ $counter }} users</p>
            <hr>
            <div>
                <h4>Comments ({{ $post->comments->count()}})</h4>
                <x-comment-form :route="route('posts.comments.store', ['post' => $post->slug])"/>
                <x-comments-list :comments="$post->comments" :size="'sm-md'"/>
            </div>
        </div>

        <div class="col-4">
            @include('posts.components._activity')
        </div>
   </div>
@endsection
