<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Option;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Posts $post) {
        $posts = $post->filterPost();
        return view('posts.index', compact('posts'));
    }

    public function show(Post $post) {
        return view('posts.show', compact('post'));
    }

    public function create() {
        return view('posts.create');
    }

    public function store() {
        $this->validate(request(), [
            'target' => 'required',
            'question' => 'required',
            'time' => 'required|numeric|min:30',
            'addmore' => 'required',
        ]);

        $user_id =  auth()->user()->id;
        $post = new Post(request(['target', 'question', 'time']));
        auth()->user()->publish($post);

        foreach (request('addmore') as $value) {
            $post->addOption($value, $user_id);
        }

        session()->flash('message', 'Your post has now been published.');

        return redirect('/home');
    }

    public function options($number) {
        $options = Post::find($number)->options;
        $optionArr = [];

        if (isset($options)) {
            foreach ($options as $value) {
                $optionArr[$value->id] = $value->option;
            }
        }
        return $optionArr;
    }
}
