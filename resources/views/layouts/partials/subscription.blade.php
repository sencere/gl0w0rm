@if ($posts)
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
@endif
