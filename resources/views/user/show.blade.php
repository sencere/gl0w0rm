@extends('layouts.app')

@section('content')
    <h3>User</h3>

    <div class='d-flex'>
        <div class='p-2'>
            @if ($channel->getImage() !== '/medium/')
                <img src="{{ $channel->getImage() }}" class="media-object">
            @else
                <img src="/fallback.png" class="media-object">
            @endif
        </div>
        <div class='user-link'>
            {{ $channel->name }}
        </div>
        <div class='p-2 ml-auto'>
        </div>
        <div class'p-2'>
            @if ($channel->description)
                <hr>
                <p>{{ $channel->description }}</p>
            @endif
        </div>
    </div>

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


@endsection
