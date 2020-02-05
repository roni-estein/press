<?php

namespace roniestein\Press\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use roniestein\Press\Post;

class PostRepository
{
    /**
     * Takes a post array and updates or creates it on the database.
     *
     * @param $post
     *
     * @return void
     */
    public function save($post)
    {
        
        $attributes = $this->getFormattedAttributesArray($post);

        Post::updateOrCreate([
            'identifier' => $post['identifier'],
        ], $attributes);
    }
    
    /**
     * Collect all of the extra fields to set it as a json string.
     *
     * @param $post
     *
     * @return false|string
     */
    private function extra($post)
    {
        $extra = (array)json_decode($post['extra'] ?? '[]');
        $attributes = Arr::except($post, ['title', 'body', 'identifier', 'published_at', 'extra']);
        
        return json_encode(array_merge($extra, $attributes));
    }
    
    private function getFormattedAttributesArray($post)
    {
        $extra = collect(
                (array)json_decode($post['extra'] ?? '{}'))
            ;
        $fields = collect($post)
            ->put('slug', Str::slug($post['title']))
            ->diffKeys($extra)
            ->except('identifier')
        ;
        
        $emptyfieldList = collect(Schema::getColumnListing((new Post)->getTable()))
            ->reject(function ($name) {
                return in_array($name, ['id','identifier','created_at','updated_at']);
            })->flatten()->flip()->map(function($item){
                return null;
            });
        
        
        return $fields->union($emptyfieldList)->toArray();
    }
}