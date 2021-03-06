<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Prediction;
use App\Models\Topic;
use App\Models\Result;
use App\Models\Vote;
use App\Models\Subscription;
use App\Models\PostView;
use Phpml\Clustering\KMeans;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    const BUFFER = 3600;

    public function index()
    {
        $user_id =  auth()->user()->id;
        $posts = Post::where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('posts.index', compact('posts'));
    }

    public function show(Request $request, Post $post)
    {
        session()->flash('breadcrumb', ['controller' => 'post','id' => $post->id]);
        $subscriptionStatus = false;
        $user = auth()->user();
        $lastUserView = $post->views()->latestByUser($user)->first();
        $views = PostView::where('post_id', $post->id)->count();
        $up = Vote::whereRaw('voteable_id = ' . $post->id . ' and type = "up"')->count();
        $down = Vote::whereRaw('voteable_id = ' . $post->id . ' and type = "down"')->count();
        $voteStatus = Vote::whereRaw('voteable_id = ' . $post->id . ' and user_id = ' . $user->id);
        $voteStatus = $voteStatus->count() ? $voteStatus->first()->type : "";

        $channel = $post->user->channel->first();
        $userSame = $post->user->id === $user->id;
        $subscription = Subscription::whereRaw('channel_id = ' . $channel->id . ' and user_id = ' . $user->id)
            ->get();

        if ($subscription->count() > 0) {
            $subscriptionStatus = true;
        }

        if (!$this->withinBuffer($lastUserView)) {
            $post->views()->create([
                'user_id' => $user->id ? $user->id : null,
                'ip' => $request->ip(),
            ]);
        }

        $data = [
            'post' => $post,
            'views' => $views,
            'up' => $up,
            'down' => $down,
            'subscription' => $subscriptionStatus,
            'vote' => $voteStatus,
            'userSame' => $userSame,
            'slug' => $channel->slug,
        ];

        return view('posts.show', $data);
    }

    public function create()
    {
        $topics = Topic::all();
        return view('posts.create', compact('topics'));
    }

    public function store()
    {
        $this->validate(request(), [
            'topic_id' => 'required|numeric|min:1',
            'question' => 'required',
            'time' => 'required|numeric|min:30',
            'option'    => 'required|array|min:2',
            'option.*'  => 'required|string|distinct|min:2',
        ]);

        $userId =  auth()->user()->id;
        $post = new Post(request(['topic_id', 'question', 'time']));
        auth()->user()->publish($post);

        foreach (request('option') as $value) {
            $post->addOption($value, $userId);
        }

        session()->flash('message', 'Your post has now been published.');
        return redirect()->back();
    }

    public function delete(Post $post)
    {
        $userId =  auth()->user()->id;

        if ($userId === $post->first()->user_id) {
            $post->first()->delete();
        }

        return redirect()->back();
    }

    public function showUpdate(Post $post)
    {
        $userId = auth()->user()->id;

        if ($userId !== $post->first()->user_id) {
            abort(404);
        }

        return view('posts.settings', compact('post'));
    }

    public function update(Post $post)
    {
        $userId = auth()->user()->id;

        if ($userId !== $post->first()->user_id) {
            abort(404);
        }

        $post->allow_votes = (request('votes') ? 1 : 0);
        $post->allow_comments = (request('comments') ? 1 : 0);

        $post->save();

        session()->flash('message', 'Your post has been updated.');
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

        // if ($resultCount > 2) {
        // $kmeans = new KMeans(10);
        // $cluster = $kmeans->cluster($attractorArray);
        // }

        // foreach ($cluster as $attractors) {
        // $x = [];
        // $y = [];
        // foreach ($attractors as $attractor) {
        // array_push($x, $attractor[0]);
        // array_push($y, $attractor[1]);
        // }

        // if (count($x) > 0 && count($y) > 0) {
        // array_push($attractorAvg, [round(array_sum($x)/count($x)), round(array_sum($y)/count($y))]);
        // }
        // }

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
                $prediction = DB::table('predictions')
                    ->select('grid', DB::raw('count(*) as total'))
                    ->groupBy('grid')
                    ->orderBy('total', 'desc')
                    ->get();

                $attractors = PredictionController::convertFromGridSystem(request('width'), request('height'), $predictions[$i]->grid, true);

                $mouseX = $attractors['x'];
                $mouseY = $attractors['y'];
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

    protected function withinBuffer($view)
    {
        return $view && $view->created_at->diffInSeconds(Carbon::now()) < self::BUFFER;
    }
}
