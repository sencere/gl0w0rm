@extends('layouts.app')

@section('content')
    <h4>Post settings update</h4>
    <hr />
    <form method="post" action="/posts/settings/{{$post->id}}">
        @csrf
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="votes" id="votes" {{ $post->allow_votes ? 'checked' : ''}}>
            <label class="custom-control-label" for="votes">Allow Votes</label>
        </div>

       <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name="comments" id="comments" {{ $post->allow_comments ? 'checked' : ''}}>
            <label class="custom-control-label" for="comments">Allow Comments</label>
        </div>

       <div class="form-group mt-3">
            <button type="submit" class="btn btn-purple">Update</button>
        </div>
    </form>
    @include('layouts.flash')
    @include('layouts.errors')
@endsection
