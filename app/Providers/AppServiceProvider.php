<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts.partials.nav', function ($view) {
            $user = \Auth::user();
            if (!is_null($user)) {
                $channel = \App\Models\Channel::where('user_id', $user->id)->get();
                $view->with('channel', $channel->first());
            }
        });

        view()->composer('layouts.partials.main', function($view) {
            $view->with('breadcrumb', \App\Models\Post::archives());
        });
    }
}
