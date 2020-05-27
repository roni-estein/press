<?php

namespace RoniEstein\Press;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    
    
    protected $table = 'press_posts';
    
    
    /**
     * @var array
     */
    protected $guarded = [];
    
    
    
    
    /**
     * Get the tags associated with the Post.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'press_post_tags');
    }

    /**
     * Easy accessor for any of the fields in the extra column.
     *
     * @param $field
     *
     * @return mixed
     */
    public function extra($field)
    {
        return optional(json_decode($this->extra))->$field;
    }
    
    
    /**
     * Get the owning author model.
     */
    public function author()
    {
        return $this->morphTo();
    }
}