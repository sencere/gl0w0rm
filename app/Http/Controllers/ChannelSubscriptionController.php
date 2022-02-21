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
        $views = PostView::where('post_id', $post->first()->id)->count();
        // $thumbsUpCount 
        $channel = $post->first()->user->channel->first();
        $subscription = Subscription::whereRaw('channel_id = ' . $channel->id . ' and user_id = ' . $userId)->get();
        $votesAllowed = $post->first()->allow_votes;
        $upVotesCount = Vote::whereRaw('voteable_id = ' . $post->first()->id . ' and type = "up"')->count();
        $downVotesCount = Vote::whereRaw('voteable_id = ' . $post->first()->id . ' and type = "down"')->count();
        $voteStatus = Vote::whereRaw('voteable_id = ' . $post->first()->id . ' and user_id = ' . $userId);
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

    // public function show(Request $request, Post $post)
    // {
        // $response = [
            // 'up' => null,
            // 'down' => null,
            // 'can_vote' => $post->fist()->votesAllowed(),
            // 'user_vote' => null,
        // ];
// 
        // if ($video->votesAllowed()) {
            // $response['up'] = $post->upVotes()->count();
            // $response['down'] = $post->downVotes()->count();
        // }
// 
        // if ($request->user()) {
            // $voteFromUser = $post->voteFromUser($request->user())->first();
            // $response['user_vote'] = $voteFromUser ? $voteFromUser->type : null;
        // }
// 
        // return response()->json([
            // 'data' => $response
        // ], 200);
    // }
}
