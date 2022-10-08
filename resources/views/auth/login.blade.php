@extends('layouts.main')

@section('main')
<form action="{{ route('login') }}" method="post">
    @csrf
    <div class="form-group">
        <label>E-Mail</label>
        <input
        type="email"
        name="email"
        value="{{ old('email') }}"
        required
        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}">

        @if($errors->has('email'))
            <span class="invalid-feedback"><strong>{{ $errors->first('email') }}</strong></span>
        @endif
    </div>

    <div class="form-group">
        <label>Password</label>
        <input
        type="password"
        name="password"
        required
        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">

        @if($errors->has('password'))
            <span class="invalid-feedback"><strong>{{ $errors->first('password') }}</strong></span>
        @endif
    </div>

    <div class="form-group mt-3">
        <div class="form-check">
            <input type="checkbox" name="remember" id="remember" class="form-check-input"
            value="{{ old('remember') ? 'ckecked' : '' }}">
            <label for="remember" class="form-check-label">Remember Me</label>
        </div>
    </div>

    <button type="submit" class="btn btn-primary container-fluid mt-4">Sign in</button>
</form>
@endsection
