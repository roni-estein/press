<?php

namespace RoniEstein\Press;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    
    
    protected $table = 'press_authors';
    
    
    /**
     * @var array
     */
    protected $guarded = [];
    
    /**
     * Get all of the author's posts.
     */
    public function posts()
    {
        return $this->morphToMany(Post::class, 'postable');
    }
}