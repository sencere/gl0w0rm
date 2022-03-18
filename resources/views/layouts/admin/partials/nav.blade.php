<nav class="navbar fixed-top navbar-toggleable-md navbar-expand-lg scrolling-navbar double-nav">
    <div class="d-flex justify-content-center bd-highlight">
        {{--    SECTION 1    --}}
        <div class="d-flex flex-nowrap p-2" style="margin-left:20px;">
            <div class="order-1">
                <a href="{{ url('/admin') }}">
                    <img src="{{ url('/logo.svg') }}" alt="" width="50" height="50" class="d-inline-block align-text-top">
                </a>
            </div>
            <div class="order-2 p-3 navbar-brand">
                <a href="/admin">
                    ADMIN
                </a>
            </div>
        </div>

        {{--    SECTION 2    --}}
        <div class="p-3">
            <div class="container-fluid">
                <ul class="navbar-nav mr-auto sidenav" id="navAccordion">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ url('/home') }}"> Startpage  </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ url('/admin') }}"> Home  </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/admin/user') }}"> User </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/admin/post') }}"> Post </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/admin/category') }}"> Category </a>
                    </li>
               </ul>
           </div>
        </div>

    </div>
</nav>
