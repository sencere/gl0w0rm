@extends('layouts.app')

@section('content')
<h4>Topics</h4>
    @if($topics->count() > 0)
        @foreach ($topics as $topic)
            <div class="card mb-3">
              <div class="card-body card-cust">
                <a class="link-text" href="{{ url('/topic/' . $topic->id  ) . '/1' }}"> 
                    <h5 class="card-title">{{ $topic->name }}</h5>
                </a>
                <a href="{{ url('/topic/' . $topic->id  ) . '/1' }}" class="btn btn-purple">Enter</a>
              </div>
            </div>
        @endforeach
    @else
        <div class="empty-page"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-file-earmark" viewBox="0 0 16 16">
  <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
</svg> There are no thread groups yet</div>
    @endif

    @if (session('status'))
        <div class="alert" role="alert">
            {{ session('status') }}
        </div>
    @endif
@endsection
