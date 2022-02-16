<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;

class CategoryController extends Controller
{
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
}
