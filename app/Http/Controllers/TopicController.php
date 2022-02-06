<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Topic;
use App\Models\Post;

class TopicController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, $topicId, $page)
    {
        $elementsPerPage = 10;
        validator($request->route()->parameters(), [
            'id' => 'required|numeric',
            'page' => 'required|numeric'
        ])->validate();

        $posts = Topic::where('topic_id', $topicId)
            ->leftJoin('posts', 'topics.id', '=', 'posts.topic_id')
            ->orderBy('posts.created_at', 'desc');

        $count = $posts->count();
        $maxPages = (int)floor($count / $elementsPerPage);

        $posts = $posts->skip(($page - 1) * $elementsPerPage)->limit($elementsPerPage)->get();

        $data = [
            'posts' => $posts,
            'maxPages' => $maxPages,
            'page' => $page,
            'topicId' => $topicId,
        ];

        return view('topic.show', $data);
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
