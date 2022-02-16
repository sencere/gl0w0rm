<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Prediction;

class PredictionController extends Controller
{
    public function store()
    {
        $this->validate(request(), [
            'postId' => 'required|numeric|min:1',
            'mouseX' => 'required|numeric|min:1',
            'mouseY' => 'required|numeric|min:1',
            'time' =>   'required|numeric|min:1'
        ]);
        $postId = request('postId');
        $userId = auth()->user()->id;

        $predictionCount = Prediction::whereRaw('user_id=' . $userId . ' and post_id=' . $postId)->get()->count();
        $predictionCount = $predictionCount === 0 ? 1 : $predictionCount + 1;

        $post = Post::find(request('postId'));
        if ($predictionCount < 11) {
            $prediction = new Prediction([
                'user_id' => $userId,
                'post_id' => $post->id,
                'attractor' => $predictionCount,
                'time' => request('time'),
                'mouseX' => request('mouseX'),
                'mouseY' => request('mouseY'),
            ]);
            $prediction->save();
        }

        response()->json(['success' => 'success'], 200);
    }
}
