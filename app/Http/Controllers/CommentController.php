<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;


class CommentController extends Controller
{
    public function store(Post $post)
    {
        $this->validate(request(), ['body' => 'required']);
        $post->addComment(request('body'));

        return back();
    }
}
