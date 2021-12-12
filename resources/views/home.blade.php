@extends('layouts.app')

@section('content')

    @if($posts)
        @foreach ($posts as $post)
            <div class="card mb-3">
              <div class="card-body">
                <a href="{{ url('/posts/' . $post->id  ) }}"> 
                    <h5 class="card-title">{{ $post->target }} {{ $post->question }}</h5>
                </a>
                <a href="{{ url('/posts/' . $post->id  ) }}" class="btn btn-primary">Enter</a>
              </div>
            </div>
        @endforeach
    @endif

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
@endsection
