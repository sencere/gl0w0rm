@extends('layouts.app')

@section('content')
    @if($categories)
        <h4>Categories</h4>
        @foreach ($categories as $category)
            <div class="card mb-3">
              <div class="card-body card-cust">
                <a class="link-text" href="{{ url('/category/' . $category->id  ) }}"> 
                    <h5 class="card-title">{{ $category->name }}</h5>
                </a>
                <a href="{{ url('/category/' . $category->id  ) }}" class="btn btn-purple">Enter</a>
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

@if (count($posts))
    @section('subscription')
        <div class="container pt-3">
            <div class="row justify-content-center">
                <div class="col-md-8">
                     <div class="card">
                        <div class="card-body">
                            <h4>Subscription</h4>
                            @foreach ($posts as $post)
                            <div class="card mb-3">
                              <div class="card-body card-cust">
                                <a class="link-text" href="{{ url('/posts/' . $post->id  ) }}">
                                    <h5 class="card-title">{{ $post->question }}</h5>
                                </a>
                                <a href="{{ url('/posts/' . $post->id  ) }}" class="btn btn-purple">Enter</a>
                              </div>
                            </div>
                            @endforeach
                         </div>
                    </div>
               </div>
            </div>
        </div>
    @endsection
@endif

