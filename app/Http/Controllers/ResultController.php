<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    public function getResult(Request $request, Post $post)
    {
        $userId =  auth()->user()->id;
        $pointsArray = [];
        $postId = $post->id;

        $result = Result::whereRaw('user_id=' .  $userId . ' and post_id=' . $postId)->get();

        $circleX = Result::where('post_id', $postId)
                    ->get()
                    ->avg('circleX');
        $circleY = Result::where('post_id', $postId)
                    ->get()
                    ->avg('circleY');

        // $prediction = DB::table('predictions')
                    // ->select('grid', DB::raw('count(*) as total'))
                    // ->groupBy('grid')
                    // ->orderBy('total', 'desc')
                    // ->get();
// 
        // $attractors = PredictionController::convertFromGridSystem(request('width'), request('height'), $prediction[0]->grid);

        // $circleX = $attractors['x'];
        // $circleY = $attractors['y'];

        if ($result->count()) {
            return [
                'result' => true,
                'confidence' => $result->first()->confidence,
                'option' => $result->first()->option,
                'circleX' => $circleX,
                'circleY' => $circleY,
            ];
        }

        return [
            'result' => false,
            'confidence' => 0,
            'option' => 0
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [

            'confidence' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'option'     => 'required|numeric|min:1',
            'postId'     => 'required|numeric|min:1|max:20',
            'circleX'     => 'required|numeric|min:1',
            'circleY'     => 'required|numeric|min:1',
        ]);

        $postId = request('postId');
        $confidence = request('confidence');
        $option = request('option');
        $circleX = request('circleX');
        $circleY = request('circleY');
        $userId =  auth()->user()->id;
        $post = Post::find(request('postId'));
        $result = Result::whereRaw('user_id=' .  $userId . ' and post_id=' . $post->id)->get()->first();

        if (empty($result)) {
            $result = new Result([
                'user_id' => $userId,
                'post_id' => $post->id,
                'option_id' => $option,
                'confidence' => $confidence,
                'circleX' => $circleX,
                'circleY' => $circleY
            ]);
            $result->save();
        }

        response()->json(['success' => 'success'], 200);
    }
}
