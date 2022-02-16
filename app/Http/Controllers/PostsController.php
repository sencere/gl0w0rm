<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Prediction;
use App\Models\Topic;
use App\Models\Result;
use Phpml\Clustering\KMeans;

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

    public function delete(Post $post) {
        $user_id =  auth()->user()->id;

        if ($user_id === $post->first()->user_id) {
            $post->first()->delete();
        }

        return redirect()->back();
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
        $sumOfSquares = 0;
        $variance = 0;
        $mean = 0;
        $attractorArray = [];
        $attractorAvg = [];
        $returnData = [];
        $timeArray = [];
        $cluster = [];
        $attractorCount = Prediction::where('post_id', '=', $postId)->get()->max('attractor');
        $resultCount = Result::where('post_id', $postId)->count();
        $predictions =  Prediction::where('post_id', $postId)->get();
        $time =  Prediction::where('post_id', $postId)->get();

        foreach ($predictions as $prediction) {
            array_push($attractorArray, [$prediction->mouseX, $prediction->mouseY]);
            array_push($timeArray, $prediction->time);
        }

        if (count($timeArray) > 0 && $resultCount > 2) {
            $mean = round(array_sum($timeArray) / count($timeArray), 2);

            foreach ($timeArray as $value) {
                $sumOfSquares += $value = pow($mean - $value, 2);
            }

            $variance = round($sumOfSquares / (count($timeArray) - 1), 2);
        }

        if ($resultCount > 2) {
            $kmeans = new KMeans(10);
            $cluster = $kmeans->cluster($attractorArray);
        }

        foreach ($cluster as $attractors) {
            $x = [];
            $y = [];
            foreach ($attractors as $attractor) {
                array_push($x, $attractor[0]);
                array_push($y, $attractor[1]);
            }

            if (count($x) > 0 && count($y) > 0) {
                array_push($attractorAvg, [round(array_sum($x)/count($x)), round(array_sum($y)/count($y))]);
            }
        }

        for($i = 0; $i < $attractorCount; $i++) {
            $time = Prediction::whereRaw('post_id=' . $postId . ' and attractor=' . ($i + 1))
                ->get()
                ->avg('time');

            if ($resultCount < 3) {
                $mouseX = Prediction::whereRaw('post_id=' . $postId . ' and attractor=' . ($i + 1))
                    ->get()
                    ->avg('mouseX');
                $mouseY = Prediction::whereRaw('post_id=' . $postId . ' and attractor=' . ($i + 1))
                    ->get()
                    ->avg('mouseY');
            } else {
                $mouseX = $attractorAvg[$i][0];
                $mouseY = $attractorAvg[$i][1];
            }

            $returnData[intval($time)] = [
                'mouseX' => $mouseX,
                'mouseY' => $mouseY
            ];
        }

        return [
            'variance' => $variance,
            'mean' => $mean,
            'predictions' => $returnData
        ];
    }
}
