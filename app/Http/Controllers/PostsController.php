<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Prediction;
use App\Models\Topic;


class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index() {
        $user_id =  auth()->user()->id;
        $posts = Post::where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post) {
        return view('posts.show', compact('post'));
    }

    public function create() {
        $topics = Topic::all();
        return view('posts.create', compact('topics'));
    }

    public function store() {
        $this->validate(request(), [
            'topic_id' => 'required|numeric|min:1',
            'question' => 'required',
            'time' => 'required|numeric|min:30',
            'option'    => 'required|array|min:2',
            'option.*'  => 'required|string|distinct|min:2',
        ]);

        $user_id =  auth()->user()->id;
        $post = new Post(request(['topic_id', 'question', 'time']));
        auth()->user()->publish($post);

        foreach (request('option') as $value) {
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
        $target = Topic::find($post->topic_id);

        if (isset($options)) {
            foreach ($options as $value) {
                $optionArr[$value->id] = $value->option;
            }
        }

        $dataArr = [
            'target' => $target->name,
            'question' => $post->question,
            'options' => $optionArr,
            'time' => $time
        ];

        return $dataArr;
    }

    public function getPostPredictions($postId) {
        $postId = intval($postId);
        $attractorAvg = [];
        $attractorCount = Prediction::where('post_id', '=', $postId)->get()->max('attractor');

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
