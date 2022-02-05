<nav class="navbar navbar-expand-sm bg-light navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item active">
      <a class="nav-link" href="/home">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/category/{{!empty($breadcrumb['category']) ? $breadcrumb['category']->name : ''}}">
            {{!empty($breadcrumb['category']) ? $breadcrumb['category']->name : ''}}
        </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/topic/{{!empty($breadcrumb['topic']) ? $breadcrumb['topic']->id : ''}}">{{!empty($breadcrumb['topic']) ? $breadcrumb['topic']->name : ''}}</a>
    </li>
    <li class="nav-item">
      <a class="nav-link disabled" href="#">{{!empty($breadcrumb['post']) ? $breadcrumb['post'] : ''}}</a>
    </li>
  </ul>
</nav>

