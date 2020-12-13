<!doctype html>
<html lang="en">
    <head>
        <title>Administrator Panel</title>
        <link rel="stylesheet" href="{{ url('css/build.css') }}">
        <link rel="icon" type="image/png" href="{{ url('images/icon.png') }}" />
    </head>
    <body>
        @if(auth()->user())
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ url('images/icon.png') }}" alt="" style="height: 35px;">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ url('/') }}">Home <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Categories
                            </a>
                            <div class="dropdown-menu" aria-labelledby="categoriesDropdown">
                                <a class="dropdown-item" href="{{ route('categories.index') }}">Manage</a>
                                <a class="dropdown-item" href="{{ route('categories.create') }}">Add</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="mediaDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Media
                            </a>
                            <div class="dropdown-menu" aria-labelledby="mediaDropdown">
                                <a class="dropdown-item" href="{{ route('media.index') }}">Manage</a>
                                <a class="dropdown-item" href="{{ route('media.create') }}">Add Media</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="tagsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Tags
                            </a>
                            <div class="dropdown-menu" aria-labelledby="tagsDropdown">
                                <a class="dropdown-item" href="{{ route('tags.index') }}">Manage</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Settings
                            </a>
                            <div class="dropdown-menu" aria-labelledby="settingsDropdown">
                                <a class="dropdown-item" href="#">Change Password</a>
                                <a class="dropdown-item" href="{{ route('settings.random') }}">Randomize Media</a>
                            </div>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @endif

        @yield('content')

        <script src="{{ url('js/build.js') }}"></script>
        <script>
            const settings = {'base_url': '{{ url('/') }}'};
        </script>
        @yield('script')
    </body>
</html>
