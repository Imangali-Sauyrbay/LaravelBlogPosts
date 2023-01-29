@extends('layouts.main')

@section('main')
    <form
    action="{{ route('authors.update', ['author' => $author->id]) }}"
    method="POST"
    enctype="multipart/form-data"
    class="form-horizontal">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-4">
                <x-avatar :author="$author" :size="'lg'"/>

                <div class="card mt-4">
                    <div class="card-body">
                        <h6>Upload a different photo</h6>
                        <input class="form-control form-control-sm" type="file" name="avatar" accept="image/*">
                    </div>
                </div>

            </div>
            <div class="col-8">
                <div class="form-group">
                    <label for="">Name:</label>
                    <input type="text" class="form-control" name="name">
                </div>
                <div class="form-group mt-2">
                    <input type="submit" class="btn btn-primary" value="Save Changes">
                </div>
            </div>
        </div>

    </form>
@endsection
