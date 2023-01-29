@extends('layouts.main')

@section('main')
        <div class="row">
            <div class="col-4">
                <x-avatar :author="$author" :size="'lg'" />
            </div>
            <div class="col-8">
                <h3 class="mb-3">{{ $author->name }}</h3>
                <x-comment-form :route="route('authors.comments.store', ['author' => $author->id])"/>
                <x-comments-list :comments="$author->commentsOn" size='sm-md'/>
            </div>
        </div>

@endsection
