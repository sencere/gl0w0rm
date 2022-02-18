<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class CategoryController extends Controller
{

    public function __construct()
    {
        // $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['store']]);
        // $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:role-delete', ['only' => ['destroy']]);
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        validator($request->route()->parameters(), [
            'name' => 'required|regex:([a-zA-Z]+)'
        ])->validate();

       $category = \App\Models\Category::where('name', '=', request('name'))->first(); 
       $topics = $category->topics;
        return view('category.show', compact('topics'));
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
