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
	<form method="POST" action="{{ route('posts.store') }}">
		@csrf
		<input class="form-control" type="text" name="title" placeholder="Title" value="{{ old('title') }}">
		<textarea class="form-control" name="content" cols="30" rows="10" placeholder="Content">{{ old('content') }}</textarea>
		@include('posts.components.error-list')
		<button class="btn btn-primary" type="submit">Create</button>
	</form>
@endsection