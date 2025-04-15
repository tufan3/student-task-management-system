<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-somehashvalue" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>


<style>
    .toast-success {
        background-color: #51A351 !important;
        color: white !important;
    }

    .toast-error {
        background-color: #BD362F !important;
        color: white !important;
    }

    .toast-info {
        background-color: #2F96B4 !important;
        color: white !important;
    }

    .toast-warning {
        background-color: #F89406 !important;
        color: white !important;
    }

    .nav-item {
    margin-right: 10px;
}
    .nav-item a{
        text-decoration: none;
}
    .nav-link.active {
        color: #0d6efd; /* blue */
        font-weight: bold;
    }
</style>


    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>


                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @if(auth()->check())
                            @if(auth()->user()->role === 'headmaster')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('teachers.index') ? 'active' : '' }}" href="{{ route('teachers.index') }}">Teachers</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('students.index') ? 'active' : '' }}" href="{{ route('students.index') }}">All Students</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('announcements.index') ? 'active' : '' }}" href="{{ route('announcements.index') }}">Announcements</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('task.approval.list') ? 'active' : '' }}" href="{{ route('task.approval.list') }}">Task Approvals</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('headmaster.deleteRequests') ? 'active' : '' }}" href="{{ route('headmaster.deleteRequests') }}">Delete Requests</a>
                                </li>
                            @elseif(auth()->user()->role === 'teacher')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('students.index') ? 'active' : '' }}" href="{{ route('students.index') }}">My Students</a>
                                </li>
                                <li class="nav-item"><a class="nav-link {{ request()->routeIs('tasks.index') ? 'active' : '' }}" href="{{ route('tasks.index') }}">Tasks</a></li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('headmaster.deleteRequests') ? 'active' : '' }}" href="{{ route('headmaster.deleteRequests') }}">Delete Requests</a>
                                </li>
                            @elseif(auth()->user()->role === 'student')
                                <li class="nav-item"><a class="nav-link {{ request()->routeIs('tasks.index') ? 'active' : '' }}" href="{{ route('tasks.index') }}">My Tasks</a></li>
                            @endif
                        @endif
                    </ul>


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @else

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="fas fa-user"></i> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user"></i> Profile</a>
                                    <a class="dropdown-item" href="{{ route('password.change', Auth::user()->id) }}"><i class="fas fa-key"></i> Change Password</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Toastr Options & Session Messages -->
<script>
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right"
    };

    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif

    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif

    @if(Session::has('info'))
        toastr.info("{{ Session::get('info') }}");
    @endif

    @if(Session::has('warning'))
        toastr.warning("{{ Session::get('warning') }}");
    @endif
</script>


</body>
</html>
