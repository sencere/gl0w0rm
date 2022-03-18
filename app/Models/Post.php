<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['topic_id', 'question', 'time', 'user_id'];

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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function addComment($body)
    {
        $userId = auth()->user()->id;

        $this->comments()->create([
            'body' => $body,
            'user_id' => $userId,
            'post_id' => $this->id
        ]);
    }

    public function views()
    {
        return $this->hasMany(PostView::class);
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function upVotes()
    {
        return $this->votes->where('type', 'up');
    }

    public function downVotes()
    {
        return $this->votes->where('type', 'down');
    }

    public function voteFromUser(User $user)
    {
        return $this->votes->where('user_id', $user->id);
    }

    public function viewCount()
    {
        return $this->views->count();
    }

    public static function archives()
    {
        $breadcrumbArray = ['category' => '', 'topic' => '', 'post' => ''];

        $breadcrumb = session('breadcrumb');

        if (isset($breadcrumb)) {
            $controller = $breadcrumb['controller'];
            $id = $breadcrumb['id'];

            switch ($controller) {
            case 'home':
                $breadcrumbArray = ['category' => '', 'topic' => '', 'post' => ''];
                break;
            case 'category':
                $category = Category::where('name', '=', $id)
                    ->first();
                $breadcrumbArray = ['category' => $category, 'topic' => '', 'post' => ''];
                break;
            case 'topic':
                $topic = Topic::find($id);
                $breadcrumbArray = ['category' => $topic->category, 'topic' => $topic, 'post' => ''];
                break;
            case 'post':
                $post = Post::find($id);
                $breadcrumbArray = ['category' => $post->topic->category, 'topic' => $post->topic, 'post' => $post->question];
                break;
            default:
                break;
            }
        }

        return $breadcrumbArray;
    }
}
