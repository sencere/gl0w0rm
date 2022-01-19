<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Post;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function getResult(Request $request)
    {
        $this->validate(request(), [
            'postId'     => 'required|numeric|min:1|max:20',
        ]);

        $userId =  auth()->user()->id;
        $post = Post::find(request('postId'));
        $result = Result::whereRaw('user_id=' .  $userId . ' and post_id=' . $post->id)->get()->first();

        if (!empty($result)) {
            $option = $result->option()->get()->first()->option;

            return [
                'result' => true,
                'confidence' => $result->confidence,
                'option' => $option
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
        ]);

        $postId = request('postId');
        $confidence = request('confidence');
        $option = request('option');
        $userId =  auth()->user()->id;
        $post = Post::find(request('postId'));
        $result = Result::whereRaw('user_id=' .  $userId . ' and post_id=' . $post->id)->get()->first();

        if (empty($result)) {
            $result = new Result([
                'user_id' => $userId,
                'post_id' => $post->id,
                'option_id' => $option,
                'confidence' => $confidence
            ]);
            $result->save();
        }

        response()->json(['success' => 'success'], 200);
    }
}
