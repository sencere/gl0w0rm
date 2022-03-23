@extends('layouts.app')

@section('content')
    <h4>[{{ $post->topic->name }}] {{$post->question}}</h4>
    <div id="landgrass" data-id="{{ $post->id }}" data-url="{{ url('') }}"></div>
    <div class="d-flex">
        <div class="p-2">
            <a href="{{ url('/user/' . $post->user->id) }}">
                @if($post->user->channel->first()->image_filename)
                    <img src="{{ url('/medium/' . $post->user->channel->first()->image_filename) }}" />
                @else
                    <img src="{{ url('fallback.png') }}" />
                @endif
            </a>
        </div>
        <div class="p-2 user-link">
            <a href="{{ url('/user/' . $post->user->id) }}">
                {{ $post->user->channel->first()->name }}
            </a>
        </div>
        <div class="p-2">
            @include('layouts.partials.eye')
            {{ $views }}
        </div>
        @if ($post->allow_votes)
            <div class="p-2 {{ $vote }}" id="votes">
                @include('layouts.partials.thumbsup-fill')
                @include('layouts.partials.thumbsup-empty')
                <span id="up-counter" data-count="{{$up}}">
                    {{ $up }}
                </span>

                @include('layouts.partials.thumbsdown-fill')
                @include('layouts.partials.thumbsdown-empty')
                <span id="down-counter" data-count="{{$down}}">
                    {{ $down }}
                </span>
            </div>
        @endif
        <div class="p-2 ml-auto">
            @if(!$userSame)
                <button type="button" class="btn {{ $subscription ? 'btn-danger subscribed' : 'btn-purple' }}" id="subscribe" data-slug="{{ $slug }}">
                    {{ $subscription ? "Subscribed" : "Subscribe" }}
                </button>
            @endif
        </div>
    </div>
@endsection
@if ($post->allow_comments)
    @section('comments')
        <div class="container pt-3">
            <div class="row justify-content-center">
                <div class="col-md-8">
                     <div class="card">
                        <div class="card-body">
                            <form method="POST" action="/posts/{{$post->id}}/comment">
                                @csrf
                                <div class="form-group">
                                    <textarea name="body" placeholder="Your comment here." class="form-control"></textarea>
                                </div>

                                <div class="form-group add-comment">
                                    <button type="submit" class="btn btn-purple">Add Comment</button>
                                </div>
                            </form>

                            <div class="comments">
                                <ul class="list-group">
                                    @foreach($post->comments as $comment)
                                        <li class="list-group-item">
                                            <a href="/user/{{ $comment->user->id }}">{{$comment->user->name }}</a>: {{ $comment->body }}<br />
                                            <strong>{{ $comment->created_at->diffForHumans() }}</strong>
                                            @if (\Auth::user()->id === $comment->user_id)
                                                <a href="/comment/delete/{{ $comment->id }}">delete</a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
               </div>
            </div>
        </div>
    @endsection
@endif

<script src="{{ asset('js/p5.min.js') }}"></script>
<script src="{{ asset('js/merged.js') }}"></script>
