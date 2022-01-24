
<!-- Image and text -->
<!--<nav class="navbar fixed-top navbar-toggleable-md navbar-expand-lg scrolling-navbar double-nav">
    <img src="{{ asset('/logo.svg') }}" style="width:50px;position:relative; padding-left:5px;" />
    <label class="navbar-brand">{{ config('app.name', 'FIREFLY') }}</label>
</nav> -->
<nav class="navbar fixed-top navbar-toggleable-md navbar-expand-lg scrolling-navbar double-nav">
    <div class="d-flex justify-content-center bd-highlight">
        {{--    SECTION 1    --}}
        <div class="d-flex flex-nowrap p-2" style="margin-left:20px;">
            <div class="order-1">
                <a href="/home">
                    <img src="/logo.svg" alt="" width="50" height="50" class="d-inline-block align-text-top">
                </a>
            </div>
            <div class="order-2 p-3 navbar-brand">
                <a href="/home">
                    {{ config('app.name', 'FIREFLY') }}
                </a>
            </div>
        </div>

        {{--    SECTION 2    --}}
        <div class="p-3">
            <div class="container-fluid">
              <form class="form-inline" action="/search" method="get">
                <input class="form-control mr-sm-2" name="q" type="search" placeholder="Search" value="{{ Request::get('q') }}" aria-label="Search">
                <button class="btn btn-purple my-2 my-sm-0" type="submit">Search</button>
              </form>
           </div>
        </div>

        {{--    SECTION 2    --}}
        <div class="d-flex flex-nowrap p-3">
            @if (Auth::guest())
            <div class="order-1 align-content-center"  style="margin-right:10px;">
                <a href="/login">Login</a>
            </div>
            <div class="order-2 align-content-center" style="margin-right:10px;">
                <a href="/register">Register</a>
            </div>
            <div class="order-3 p-3">&nbsp;</div>
            <div class="order-4 p-3">&nbsp;</div>
            @else
            <div class="order-1" style="margin-right:15px;">
                <div class="dropdown show">
                    <a class="btn btn-purple dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->name }} 
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" href="#">Your videos</a>
                        <a class="dropdown-item" href="#">Your channel</a>
                        <a class="dropdown-item" href="#">Channel settings</a>

                        <a class="dropdown-item" href="{{ url('/logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>

            <div class="order-2" style="margin-right:5px;">
                <a href="/posts/create">
                    <button type="button" class="btn btn-purple">Create</button>
                </a>
            </div>
            @endif
        </div>
    </div>
</nav>
<!--
<nav class="navbar fixed-top navbar-toggleable-md navbar-expand-lg scrolling-navbar double-nav">
 <img src="{{ asset('/logo.svg') }}" style="width:50px;position:relative; padding-left:5px;" />
            <a class="navbar-brand" href="{{ url('home') }}">
                {{ config('app.name', 'FIREFLY') }}
            </a>
</nav> -->
<!--
<nav class="navbar navbar-default" role="navigation">
    </nav>
-->
