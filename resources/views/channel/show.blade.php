@extends('layouts.app')

@section('content')
    <h1>Channel</h1>
    <div class="panel panel-default">
        <div class="panel-body">
                   <div class="media">
                        <div class="media-left">
                            <img src="{{ $channel->getImage() }}" alt="{{ $channel->name }} image" class="media-object">
                        </div>
                        <div class="media-body">
                            {{ $channel->name }}
                            <ul class="list-inline">
                                <li>
                                    <subscribe-button channel-slug="{{ $channel->slug }}"></subscribe-button>
                                </li>
                           </ul>
                            @if ($channel->description)
                                <hr>
                                <p>{{ $channel->description }}</p>
                            @endif
                   </div>
            </div>
        </div>
    </div>
@endsection
