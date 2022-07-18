@extends('layouts.main')

@section('head')
	<style>
		form {
			display: flex;
			flex-direction: column;
			width: 40%;
			margin: auto;
		}

		form > input {
			height: 30px;
			margin-bottom: 15px;
		}

		form > textarea {
			resize: vertical;
		}

		form > button {
			margin-top: 15px; 
			width: fit-content;
			align-self: center;
		}

		@media (max-width:765px) {
			form {
				width: 90%;
			}
		}
	</style>
@endsection

@section('main')
	<form method="POST" action="{{ route('posts.update', ['post' => $post->slug]) }}">
		@csrf
		@method('put')
		<input
		type="text"
		name="title"
		placeholder="Title"
		value="{{ old('title', $post->title) }}">

		<textarea
		name="content"
		cols="30"
		rows="10"
		placeholder="Content">{{--
	--}}{{ old('content', $post->content) }}{{--
	--}}</textarea>

		@include('posts.components.error-list')
		<button type="submit">Save</button>
	</form>
	<form action="{{ route('posts.destroy', ['post' => $post->slug]) }}" method="POST">
		@csrf
		@method('DELETE')
		<button type="submit" style="color: red;">Delete Post</button>
	</form>
@endsection