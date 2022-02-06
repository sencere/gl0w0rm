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
        view()->composer('layouts.partials.subscription', function ($view) {
            $subscriptionUserIdArray = [];
            $user = \Auth::user();
            $subscriptions = \App\Models\Subscription::where('user_id', $user->id)
                ->get();

            foreach ($subscriptions as $subscription) {
                $channelId = $subscription->channel_id;
                $channel = \App\Models\Channel::find($channelId);
                if (!in_array($channel->user_id, $subscriptionUserIdArray)) {
                    array_push($subscriptionUserIdArray, $channel->user_id);
                }
            }

            if (!is_null($user)) {
                $posts = [];

                $posts = \DB::table('posts')
                    ->whereIn('user_id', $subscriptionUserIdArray)
                    ->latest()
                    ->limit(5)
                    ->get();

                $view->with('posts', $posts);
            }
        });

        view()->composer('layouts.partials.nav', function ($view) {
            $user = \Auth::user();
            if (!is_null($user)) {
                $channel = \App\Models\Channel::where('user_id', $user->id)->get();
                $view->with('channel', $channel->first());
            }
        });

        view()->composer('layouts.partials.breadcrumb', function($view) {
            $view->with('breadcrumb', \App\Models\Post::archives());
        });
    }
}
