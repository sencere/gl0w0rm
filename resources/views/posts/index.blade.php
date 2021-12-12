@extends('layouts.master')

@section('content')
    @foreach ($posts as $post)
        @include('posts.post')
    @endforeach

    @include('posts.nav')
@endsection
