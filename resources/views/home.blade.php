@extends('layouts.homeapp')

@section('content')
    @if($categories)
    <h4>Category</h4>
        @foreach ($categories as $category)
            <div class="card mb-3">
              <div class="card-body card-cust">
                <a class="link-text" href="{{ url('/category/' . $category->name  ) }}"> 
                    <h5 class="card-title">{{ $category->name }}</h5>
                </a>
                <a href="{{ url('/category/' . $category->name  ) }}" class="btn btn-purple">Enter</a>
              </div>
            </div>
        @endforeach
    @endif
    @if (session('status'))
        <div class="alert" role="alert">
            {{ session('status') }}
        </div>
    @endif
@endsection
