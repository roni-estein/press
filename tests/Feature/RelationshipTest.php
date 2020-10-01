<?php

namespace RoniEstein\Press\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use RoniEstein\Press\Post;
use RoniEstein\Press\Tag;

class RelationshipTest extends TestCase
{
    use RefreshDatabase;
    
    protected $author;
    
    
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
        $tags = factory(Tag::class, 2)->create();
        $post->tags()->sync($tags);
        
        $this->assertCount(2, Tag::all());
        $this->assertCount(2, $post->tags);
    }
    
    
    /** @test */
    public function a_tag_can_have_many_posts(): void
    {
        $tag = factory(Tag::class)->create();
        $posts = factory(Post::class, 2)->create();
        $tag->posts()->sync($posts);
        
        $this->assertCount(2, Post::all());
        $this->assertCount(2, $tag->posts);
    }
    
    /** @test */
    public function a_post_has_an_author(): void
    {
        $post = factory(Post::class)->create();
        $post->authors()->sync([
            'post_id' => $post->id,
            'postable_id' => $this->author->id,
            'postable_type' => get_class($this->author),
        
        ]);
        
        $this->assertTrue($post->authors->first()->is($this->author));
        
    }
    
    /** @test */
    public function an_author_owns_one_or_more_posts(): void
    {
        $author = $this->author;
        $posts = factory(Post::class, 2)->raw();
        $author->posts()->createMany($posts);
        
        $this->assertJsonSubset($posts, $author->posts);
        
    }
    
    
    public function setUp(): void
    {
        parent::setUp();
        
        // an author is required to exist in the database for every possible blog post test
        // except failure test
        
        $this->author = factory('RoniEstein\Press\Author')->create(['slug' => 'juan-valdez']);
        
    }
}