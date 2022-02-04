<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Channel;

class UserController extends Controller
{
    public function show(User $user) {
        $posts = [];
        return view('user.show', compact('posts'));
    }
}
