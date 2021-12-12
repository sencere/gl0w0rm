<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['target', 'question', 'time', 'user_id'];

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
}
