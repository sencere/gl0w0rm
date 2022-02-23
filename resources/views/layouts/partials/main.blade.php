<main>
    @include ('layouts.partials.breadcrumb')
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                       @yield('content')
                     </div>
                </div>
            </div>

        </div>
    </div>
    <div class="container pt-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @yield('subscription')
                @yield('comments')
            </div>
        </div>
    </div>
</main>
