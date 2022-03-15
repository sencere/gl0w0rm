@extends('layouts.app')

@section('content')
    <h1>Channel</h1>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="d-flex">
                <div class="p-2">
                    @if ($channel->getImage() !== '/medium/')
                        <img src="{{ $channel->getImage() }}" class="media-object">
                    @else
                        <img src="{{ url('/fallback.png') }}" class="media-object">
                    @endif
                </div>
                <div class="user-link">
                    {{ $channel->name }}
                </div>
            </div>

            <div>
                @if ($channel->description)
                    {{ $channel->description }}
                @endif
            </div>
        </div>
    </div>
@endsection
