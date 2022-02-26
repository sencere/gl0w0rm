@extends('layouts.app')

@section('content')
    <h4>[{{ $post->topic->name }}] {{$post->question}}</h4>
    <div id="landgrass" data-id="{{ $post->id }}"></div>
    @include('layouts.errors')
@endsection
@if ($post->allow_comments) 
    @section('comments')
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
    @endsection
@endif
