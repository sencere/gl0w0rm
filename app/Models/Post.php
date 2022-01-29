<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Topic;
use App\Models\Post;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['target',  'topic_id', 'question', 'time', 'user_id'];

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function predictions()
    {
        return $this->hasMany(Prediction::class);
    }

    public function addOption($option, $user_id)
    {
        $this->options()->create(compact('option', 'user_id'));
    }

    public function addPrediction($body)
    {
        $this->predictions()->create(compact('body'));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the result for the blog post.
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public static function archives()
    {
        $breadcrumbArray = ['category' => '', 'topic' => '', 'post' => ''];
        $arr = explode('/', $_SERVER['REQUEST_URI']);

        if ($arr[1] === 'category') {
            $category = Category::where('name', '=', $arr[2])
                ->first();
            $breadcrumbArray = ['category' => $category, 'topic' => '', 'post' => ''];
        }

        if ($arr[1] === 'topic') {
            $topic = Topic::find($arr[2]);
            $breadcrumbArray = ['category' => $topic->category, 'topic' => $topic, 'post' => ''];
        }

        if ($arr[1] === 'posts') {
            $checkArray = ['create', 'show'];
            if (in_array($arr[2], $checkArray)) {
                return;
            }

            $post = Post::find($arr[2]);
            $breadcrumbArray = ['category' => $post->topic->category, 'topic' => $post->topic, 'post' => $post->question];
        }

        return $breadcrumbArray;
    }
}
