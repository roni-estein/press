<?php

namespace RoniEstein\Press\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RoniEstein\Press\Post;
use RoniEstein\Press\Tag;

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
        
        abort_unless(is_array($attributes['authors']),
            422, 'No author present on the blog post: '
            . $attributes['title']);
        
        $authors = Arr::pull($attributes, 'authors');
        
        $currentPost = Post::updateOrCreate([
            'identifier' => $post['identifier'],
        ], $attributes);
        
        
        //Possible that authors are not all of the same class
        $authors = collect($authors)->map(function ($item) use ($currentPost) {
            $item['post_id'] = $currentPost->id;
            return $item;
        });
    
        $currentPost->authors()->detach();
        
        $currentPost->authors()->sync($authors);
        
        $this->saveOrUpdateTagsOn($currentPost, $post['tags'] ?? '');
        
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
        $attributes = Arr::except($post, ['title', 'body', 'identifier', 'published_at', 'extra', 'tags']);
        
        return json_encode(array_merge($extra, $attributes));
    }
    
    private function getFormattedAttributesArray($post)
    {
        
        $extra = collect((array)json_decode($post['extra'] ?? '{}', true));
        $fields = collect($post)
            ->put('slug', Str::slug($post['title']))
            ->diffKeys($extra)
            ->except('identifier', 'tags', 'author');

        $emptyfieldList = collect(Schema::getColumnListing((new Post)->getTable()))
            ->reject(function ($name) {
                return in_array($name, ['id', 'identifier', 'created_at', 'updated_at']);
            })->flatten()->flip()->map(function ($item) {
                return null;
            });
        
        
        return $fields->union($emptyfieldList)->toArray();
    }
    
    
    private function saveOrUpdateTagsOn($post, string $tagString = '')
    {
        
        $tags = Collection::make(explode(',', $tagString));
        
        if ($tags->isNotEmpty()) {
            
            $tags = $tags
                ->reject(function ($tag) {
                    return empty(trim($tag));
                })
                ->transform(function ($tag) {
                    return Tag::firstOrCreate([
                        'slug' => Str::slug($tag),
                    ], [
                        'text' => trim(Str::lower($tag)),
                    ]);
                });
    
            $post->tags()->sync($tags);
            
            Tag::prune();
        } else {
            $post->tags()->detach();
        }
    }
}