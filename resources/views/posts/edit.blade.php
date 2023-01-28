@extends('layouts.main')

@section('head')
	<style>
		form {
			display: flex;
			flex-direction: column;
			width: 60%;
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
<form method="POST" action="{{ route('posts.update', ['post' => $post->slug]) }}" enctype="multipart/form-data">
    @csrf
    @method('put')
    <input
    class="form-control"
    type="text"
    name="title"
    placeholder="Title"
    value="{{ old('title', $post->title) }}">

    <textarea
    class="form-control"
    name="content"
    cols="30"
    rows="10"
    placeholder="Content">{{ old('content', $post->content) }}</textarea>

    @include('posts.components._input_file')

    <x-errors />
    <button type="submit" class="btn btn-outline-success">Save</button>
</form>

<form action="{{ route('posts.destroy', ['post' => $post->slug]) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">Delete Post</button>
</form>

@endsection
