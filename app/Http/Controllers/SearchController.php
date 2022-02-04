<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Post;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->q) {
            return redirect('/home');
        }

        $posts = Post::where('question', 'LIKE', '%' . $request->q . '%')
                    ->get();

        return view('search.index', [
            'posts' => $posts,
        ]);
    }
}
