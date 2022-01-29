<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Prediction;

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
        $post = new Post(request(['target', 'topic_id', 'question', 'time']));
        auth()->user()->publish($post);

        foreach (request('addmore') as $value) {
            $post->addOption($value, $user_id);
        }

        session()->flash('message', 'Your post has now been published.');

        return redirect()->home();
    }

    public function getPostOptions($number) {
        $post = Post::find($number);
        $options = $post->options;
        $time = $post->time;
        $optionArr = [];
        $dataArr = [];

        if (isset($options)) {
            foreach ($options as $value) {
                $optionArr[$value->id] = $value->option;
            }
        }

        $dataArr = [
            'target' => $post->target,
            'question' => $post->question,
            'options' => $optionArr,
            'time' => $time
        ];

        return $dataArr;
    }

    public function getPostPredictions($postId) {
        $postId = intval($postId);
        $attractorAvg = [];
        $attractorCount = Prediction::where('post_id', '=', 1)->get()->max('attractor');

        for($i = 0; $i < $attractorCount; $i++) {
            $time = Prediction::whereRaw('post_id=' . $postId . ' and attractor=' . ($i + 1))
                ->get()
                ->avg('time');
            $mouseX = Prediction::whereRaw('post_id=' . $postId . ' and attractor=' . ($i + 1))
                ->get()
                ->avg('mouseX');
            $mouseY = Prediction::whereRaw('post_id=' . $postId . ' and attractor=' . ($i + 1))
                ->get()
                ->avg('mouseY');

            $attractorAvg[intval($time)] = [
                'mouseX' => $mouseX,
                'mouseY' => $mouseY
            ];
        }
        return $attractorAvg;
    }
}
