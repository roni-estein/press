<?php

namespace RoniEstein\Press;

use RoniEstein\Press\Facades\Press;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    
    
    protected $table = 'press_posts';
    
    
    /**
     * @var array
     */
    protected $guarded = [];
    
    protected $casts = [
        'published_at' => 'datetime',
    ];
    
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
     * Get the owning authors model.
     */
    public function authors()
    {
        return $this->morphedByMany(Press::authorModel(), 'postable');
    }
    
    
    /**
     * Scope a query to only include Published posts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopePublished($query)
    {
        return $query
            ->whereNotNull('published_at')
            ->whereDate('published_at','<=', now(Press::timezone()));
    }
    
    /**
     * Scope a query to only include Published posts.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeUnpublished($query)
    {
        return $query->whereNull('published_at');
    }
    
    /**
     * Scope a query to order posts by published at where they are published.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeRecent($query)
    {
        return $query->whereNotNull('published_at')->OrderBy('published_at', 'desc');
    }
    
}