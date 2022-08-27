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
    <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
        <h5 class="my-0 my-md-auto font-weight-normal">Laravel Blog</h5>
        <nav class="my-2 my-md-0 mr-md-3">
            <a href="{{route('root')}}" class="p-2 text-dark">Main</a>
            <a href="{{route('contacts')}}" class="p-2 text-dark">Contacts</a>
            <a href="{{route('posts.index')}}" class="p-2 text-dark">Posts</a>
            <a href="{{route('posts.create')}}" class="p-2 text-dark">New Post</a>
        </nav>
    </div>

    <main class="container">
         @if (session()->has('status'))
            <h5 id="status" style="text-align: center; color: lightgreen;">{{ session()->get('status') }}</h5>
        @endif
        @yield('main')
    </main>

    @yield('scripts')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            var statusBar = document.getElementById('status');
            if(statusBar) setTimeout(() => statusBar.parentElement.removeChild(statusBar), 5000);
        })
    </script>
</body>
</html>
