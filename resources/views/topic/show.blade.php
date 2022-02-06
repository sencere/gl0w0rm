@extends('layouts.app')

@section('content')
    @if($posts)
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
    @if (session('status'))
        <div class="alert" role="alert">
            {{ session('status') }}
        </div>
    @endif
@endsection
