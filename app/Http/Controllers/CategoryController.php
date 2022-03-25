<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:role-admin', ['only' => ['store']]);
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $reqeust, $categoryId, $page)
    {
        $elementsPerPage = 10;
        session()->flash('breadcrumb', ['controller' => 'category', 'id' => $categoryId]);

        $topics = Category::whereRaw('categories.id=' . $categoryId)
            ->leftJoin('topics', 'categories.id', '=', 'topics.category_id')
            ->orderBy('topics.created_at', 'desc');

        $count = $topics->count();
        $maxPages = (int)floor($count / $elementsPerPage);
        $topics = $topics->skip(($page -1) * $elementsPerPage)->limit($elementsPerPage)->get();

        $data = [
            'topics' => $topics,
            'maxPages' => $maxPages,
            'page' => $page,
            'categoryId' => $categoryId,
        ];

        return view('category.show', $data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        validator(request()->all(), [
            'name' => 'required|regex:([a-zA-Z]+)'
        ])->validate();

        $category = new Category(request(['name']));

        auth()->user()->createCategory($category);

        session()->flash('message', 'Your category has now been published.');
        return back();
    }
}
