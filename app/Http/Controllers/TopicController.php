<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;

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

        $topic = \App\Models\Topic::find(request('id'));
        $posts = $topic->posts;
        return view('topic.show', compact('posts'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create(Request $request)
    {
        $categories = Category::all();
        return view('topic.create', compact('categories'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'category_id' => 'required|numeric|min:1',
            'name' => 'required',
        ]);

        $userId =  auth()->user()->id;
        $topic = new Topic(request(['category_id', 'name']));

        auth()->user()->createTopic($topic);

        session()->flash('message', 'Your topic has now been published.');
        return back();
    }

}
