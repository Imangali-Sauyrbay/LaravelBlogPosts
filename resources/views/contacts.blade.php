@extends('layouts.main')

@section('main')
    <h1>Contacts</h1>

    @can('home.contact.secret')
        <p>
            <a href="{{ route('secret') }}">Special Link</a>
        </p>
    @endcan
@endsection
