<?php

namespace RoniEstein\Press;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];
    
    protected $table = 'press_tags';
    
    /**
     * Get the posts associated with the Tag.
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class,'press_post_tags','post_id','tag_id');
    }
    
}