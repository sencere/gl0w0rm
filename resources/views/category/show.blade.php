@extends('layouts.app')

@section('content')
<h4>Topic</h4>
    @if($topics)
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
    @endif

    @if (session('status'))
        <div class="alert" role="alert">
            {{ session('status') }}
        </div>
    @endif
@endsection
