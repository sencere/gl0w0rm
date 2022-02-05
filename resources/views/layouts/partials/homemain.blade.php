<main class="py-4 pt-5 mt-5">
    @include ('layouts.partials.breadcrumb')
    <div class="container pt-3">
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
    @include ('layouts.partials.subscription')
</main>
