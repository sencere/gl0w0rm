@extends('layouts.app')
@section('content')
    <h4>Posts</h4>
    @if($posts->count() > 0)
        @foreach ($posts as $post)
            <div class="card mb-3">
              <div class="card-body card-cust">
                <a class="link-text" href="{{ url('/posts/' . $post->id  ) }}"> 
                    <h5 class="card-title">{{ $post->target }} {{ $post->question }}</h5>
                </a>
                <a href="{{ url('/posts/' . $post->id  ) }}" class="btn btn-purple">Enter</a>
              </div>
            </div>
        @endforeach
    @else
        <div class="empty-page"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
  <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
</svg> There are no threads yet</div>
    @endif

    @if ($maxPages > 0)
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                @if($page - 1 !== 0)
                    <li class="page-item"><a class="page-link" href="/topic/{{$topicId}}/{{$page - 1}}">Previous</a></li>
                @endif
                @for ($i = 0; $i < $maxPages; $i++ )
                    <li class="page-item"><a class="page-link" href="/topic/{{$topicId}}/{{ $i + 2 }}">{{ $i + 2 }}</a></li>
                @endfor
                @if ($page - 1 < $maxPages)
                    <li class="page-item"><a class="page-link" href="/topic/{{$topicId}}/{{ $i + 1 }}">Next</a></li>
                @endif
            </ul>
        </nav>
    @endif
@endsection
