<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Channel;
use App\Models\User;

class UserController extends Controller
{
    public function show(User $user) {
        $posts = Post::where('user_id', $user->id)
                    ->limit(10)
                    ->get();
        $channel = Channel::find($user->id);

        $data = [
            'channel'  => $channel,
            'posts'   => $posts,
        ];

        return \View::make('user.show')->with($data);
    }
}
