<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\CreateVoteRequest;

class PostVoteController extends Controller
{
    public function create(CreateVoteRequest $request, Post $post)
    {
        $user = auth()->user();
        if ($post->first()->voteFromUser($user)->count() > 0) {
            $post->first()->voteFromUser($user)->first()->delete();
        }

        $post->votes()->create([
            'type' => $request->type,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(null, 200);
    }

    public function remove(Request $request, Post $post)
    {
        $user = auth()->user();
        $post->voteFromUser($user)->first()->delete();

        return response()->json(null, 200);
    }
}
