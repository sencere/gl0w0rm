@extends('layouts.app')

@section('content')
    <h4>{{ $post->target }} {{$post->question}}</h4>
    <div id="landgrass" data-id="{{ $post->id }}"></div>
<script>
    import React from 'react';
    import ReactDOM from 'react-dom';
    import Application from './Application';

    ReactDOM.render(<Application />, landgrass);
</script>
    @include('layouts.errors')
@endsection
