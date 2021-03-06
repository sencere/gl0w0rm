@extends('layouts.app')

@section('content')
    <h3>Search for "{{ Request::get('q') }}"</h3>
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
@endsection
