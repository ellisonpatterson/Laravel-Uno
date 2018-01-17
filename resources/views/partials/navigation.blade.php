<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand" href="{{ route('index') }}">{{ config('app.name', 'Laravel') }}</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle Navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav ml-auto">
            @if (Auth::check())
                <li class="nav-item dropdown">
                    <button class="btn btn-dark dropdown-toggle py-0" type="button" id="userMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="avatar-s rounded-circle" src="{{ asset(auth()->user()->avatar) }}"></img>
                        <span class="nav-link d-inline-block py-0">{{ auth()->user()->username }}</span>
                    </button>

                    <div class="dropdown-menu" aria-labelledby="userMenu">
                        <a id="trigger-logout" class="dropdown-item" href="{{ route('auth.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log-Out</a>
                        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                    </div>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('auth.login') }}">Login</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('auth.register') }}">Register</a>
                </li>
            @endif
        </ul>
    </div>
</nav>