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
            'postId' => 'required|numeric|min:1|max:20',
            'mouseX' => 'required|numeric|min:1',
            'mouseY' => 'required|numeric|min:1'
        ]);

        $userId =  auth()->user()->id;

        $predictionCount = Prediction::where('user_id', '=', $userId)->get()->count();
        $post = Post::find(request('postId'));
        if ($predictionCount < 10) {
            $prediction = new Prediction([
                'mouseX' => request('mouseX'),
                'mouseY' => request('mouseY'),
                'user_id' => $userId,
                'post_id' => $post->id
            ]);
            $prediction->save();
        }

        response()->json(['success' => 'success'], 200);
    }
}
