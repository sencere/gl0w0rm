<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class AdminController extends Controller
{
     public function __construct()
    {
        $this->middleware('permission:role-admin', ['only' => ['index']]);
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index() {
        $userCount = User::all()->count();
        $postCount = Post::all()->count();
        $statistics = [
            'users' => $userCount,
            'posts' => $postCount,
        ];
        return view('admin.index', compact('statistics'));
    }

    public function user() {
        $users = User::all();
        return view('admin.user', compact('users'));
    }

    public function post() {
        $posts = Post::all();
        return view('admin.post', compact('posts'));
    }

    public function category() {
        return view('admin.category', []);
    }
}
