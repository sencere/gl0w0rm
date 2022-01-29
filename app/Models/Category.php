<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Get the topics for the category.
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
