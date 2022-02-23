<nav class="navbar navbar-expand-lg navbar-light fixed-top">
    <a href="/home">
        <img src="/logo.svg" alt="" width="50" height="50" class="d-inline-block align-text-top">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse " id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active p-3">
                <a href="/home">Home <span class="sr-only">(current)</span></a>
            </li>
            @if (Auth::guest())
                <li class="nav-item active p-3">
                    <a href="/login">Login</a>
                </li>
                <li class="nav-item active p-3">
                    <a href="/register">Register</a>
                </li>
            @else
                <li class="nav-item dropdown p-3">
                    <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }} 
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        @if(Auth::user()->roles->pluck('name')->first() === 'Admin')
                            <a class="dropdown-item" href="/admin">Admin Panel</a>
                        @endif
                        <a class="dropdown-item" href="/topic/create">Create a new topic</a>
                        <a class="dropdown-item" href="/posts/index">Your posts</a>
                        <a class="dropdown-item" href="{{ url('/channel/' . $channel->slug) }}">Your channel</a>
                        <a class="dropdown-item" href="{{ url('/channel/' . $channel->slug . '/edit') }}">Settings</a>
                        <a class="dropdown-item" href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
                <li class="nav-item p-3">
                    <a href="/posts/create"> Create </a>
                </li>
            @endif
        </ul>
        <form class="form-inline my-2 my-lg-0" action="/search" method="get">
            <input class="form-control mr-sm-2" name="q" type="search" placeholder="Search" value="{{ Request::get('q') }}" aria-label="Search">
            <button class="btn btn-purple my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>
