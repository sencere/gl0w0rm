@extends('layouts.admin.app')

@section('content')
    <h1>Create a new category</h1>
    <hr />
    <form method="post" action="/category">
        @csrf
        <div class="form-group">
            <label for="category">Category:</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-purple">Create</button>
        </div>
    </form>
    @include('layouts.errors')
    @include('layouts.flash')
@endsection
