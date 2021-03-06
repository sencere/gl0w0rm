<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostView;
use App\Models\Subscription;
use App\Models\Vote;

class ChannelSubscriptionController extends Controller
{
    public function show(Request $request, Channel $channel)
    {
        $response = [
            'count' => $channel->subscriptionCount(),
            'user_subscribed' => false,
            'can_subscribe' => false,
        ];

        if ($request->user()) {
            $response = array_merge($response, [
                'user_subscribed' => $request->user()->isSubscribedTo($channel),
                'can_subscribe' => !$request->user()->ownsChannel($channel),
            ]);
        }

        return response()->json([
            'data' => $response
        ], 200);
    }

    public function create(Request $request, Channel $channel)
    {
        // $this->authorize('subscribe', $channel);

        $request->user()->subscriptions()->create([
            'channel_id' => $channel->id
        ]);

        return response()->json(null, 200);
    }

    public function delete(Request $request, Channel $channel)
    {
        // $this->authorize('unsubscribe', $channel);

        $request->user()->subscriptions()->where('channel_id', $channel->id)->delete();

        return response()->json(null, 200);
    }

    public function getSubscriptionStatus(Post $post)
    {
        $subscriptionStatus = "unsubscribed";
        $voteStatus = "";
        $userId =  auth()->user()->id;
        $views = PostView::where('post_id', $post->id)->count();
        // $thumbsUpCount 
        $channel = $post->user->channel->first();
        $subscription = Subscription::whereRaw('channel_id = ' . $channel->id . ' and user_id = ' . $userId)->get();

        $votesAllowed = $post->allow_votes;
        $upVotesCount = Vote::whereRaw('voteable_id = ' . $post->id . ' and type = "up"')->count();
        $downVotesCount = Vote::whereRaw('voteable_id = ' . $post->id . ' and type = "down"')->count();
        $voteStatus = Vote::whereRaw('voteable_id = ' . $post->id . ' and user_id = ' . $userId);
        $voteStatus = $voteStatus->count() ? $voteStatus->first()->type : "";
        if ($subscription->count() > 0) {
            $subscriptionStatus = 'subscribed';
        }

        if ($post->first()->user_id === $userId) {
            $subscriptionStatus = 'hidden';
        }

        return [
            'status' => $subscriptionStatus,
            'slug' => $channel->slug,
            'channelName' => $channel->name,
            'imageFileName' => $channel->image_filename,
            'userId' => $userId,
            'views' => $views,
            'votesAllowed' => $votesAllowed,
            'upVotesCount' => $upVotesCount,
            'downVotesCount' => $downVotesCount,
            'voteStatus' => $voteStatus,
        ];
    }
}
