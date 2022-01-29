<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopicController extends Controller
{
     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        validator($request->route()->parameters(), [
            'id' => 'required|numeric'
        ])->validate();

       $topic = \App\Models\Topic::find(request('id'))->first(); 
       $posts = $topic->posts;
        return view('topic.show', compact('posts'));
    }
}
