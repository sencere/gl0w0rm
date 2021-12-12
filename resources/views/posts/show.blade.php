@extends('layouts.app')

@section('content')
    <h4>{{ $post->target }} {{$post->question}}</h4>
    <div id="landgrass" data-id="{{ $post->id }}"></div>
    @include('layouts.errors')
@endsection
