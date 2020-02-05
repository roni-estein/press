<?php

namespace roniestein\Press\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use roniestein\Press\Post;
use roniestein\Press\Tag;

class SavePostsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_post_can_be_created_with_the_factory()
    {
        $post = factory(Post::class)->create();
        
        $this->assertCount(1, Post::all());
    }
    
    /** @test */
    public function a_post_can_have_many_tags(): void
    {
        $post = factory(Post::class)->create();
        $tags = factory(Tag::class,2)->create();
        $post->tags()->sync($tags);
        
        $this->assertCount(2,Tag::all());
        $this->assertCount(2,$post->tags);
    }
    
    
    /** @test */
    public function a_tag_can_have_many_posts(): void
    {
        $tag = factory(Tag::class)->create();
        $posts = factory(Post::class,2)->create();
        $tag->posts()->sync($posts);
        
        $this->assertCount(2,Post::all());
        $this->assertCount(2,$tag->posts);
    }
}