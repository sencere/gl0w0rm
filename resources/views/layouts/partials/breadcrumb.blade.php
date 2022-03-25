<nav class="navbar navbar-expand-sm bg-light navbar-light mt-5 pt-5">
  <ul class="navbar-nav">
    <li class="nav-item active">
      <a class="nav-link" href="{{ url('/home') }}">Home</a>
    </li>
    <li class="nav-item">
        @if(!empty($breadcrumb['category']))
            <a class="nav-link" href="{{ url('/category/' . $breadcrumb['category']->id . '/1') }}">
               {{ $breadcrumb['category']->name }}
            </a>
        @endif
    </li>
    <li class="nav-item">
        @if(!empty($breadcrumb['topic']))
            <a class="nav-link" href="{{ url('/topic/' . $breadcrumb['topic']->id . '/1') }}">
                {{ $breadcrumb['topic']->name }}
            </a>
        @endif
    </li>
    <li class="nav-item">
        <a class="nav-link disabled" href="#">
            {{ $breadcrumb['post'] }}
        </a>
    </li>
  </ul>
</nav>
