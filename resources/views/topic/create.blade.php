@extends('layouts.app')

@section('content')
    <h1>Create a new topic</h1>
    <hr />
    <form method="post" action="/topics">
        @csrf
        <div class="form-group">
            <label for="category">Category:</label>
            <select class="browser-default custom-select" id="category" name="category_id">
                <option selected>Please select a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"> {{ $category->name }} </option>
                @endforeach
            </select>
        </div>


        <div class="form-group">
            <label for="topic">Topic:</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-purple">Create</button>
        </div>
    </form>
    @include('layouts.errors')
@endsection
