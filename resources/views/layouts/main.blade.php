<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @yield('head')
</head>
<body>
    <div class="d-flex flex-column flex-md-row justify-content-md-around align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
        <a href="{{ route('root') }}" class="text-dark text-decoration-none"><h5 class="my-0 my-md-auto font-weight-normal">Laravel Blog</h5></a>
        <nav class="my-2 my-md-0 mr-md-3">
            <a href="{{route('root')}}" class="p-2 text-dark">Main</a>
            <a href="{{route('contacts')}}" class="p-2 text-dark">Contacts</a>
            <a href="{{route('posts.index')}}" class="p-2 text-dark">Posts</a>
            <a href="{{route('posts.create')}}" class="p-2 text-dark">New Post</a>
            <span class="m-0">|</span>
            @auth
                <form method="POST" action="{{route('logout')}}" class="d-inline-block">
                    @csrf
                    <button type="submit" class="btn p-2 text-dark text-decoration-underline">Logout ({{ auth()->user()->name }})</button>
                </form>
            @else
                <a href="{{route('register')}}" class="p-2 text-dark">Sign up</a>
                <a href="{{route('login')}}" class="p-2 text-dark">Sign in</a>
            @endauth
        </nav>
    </div>

    <main class="container">
        @if(session()->has('status'))
            <h5 id="status" style="text-align: center; color: lightgreen;">{{ session()->get('status') }}</h5>
        @endif

        @yield('main')
    </main>

    <footer class="bg-light py-1 fixed-bottom border-top border-1 border-gray-800 text-center text-muted">
        <span class="m-1">Laravel App Version: {{ App::version() }}</span> |
        <span class="m-1">PHP Version: {{ phpversion() }}</span>
    </footer>


    @yield('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', function () {
	        var statusBar = document.getElementById('status');
	        if(statusBar) setTimeout(function () { statusBar.parentElement.removeChild(statusBar)}, 5000);
	    })
    </script>
</body>
</html>
