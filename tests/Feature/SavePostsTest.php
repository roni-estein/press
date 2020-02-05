<?php

namespace roniestein\Press\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use roniestein\Press\Post;
use roniestein\Press\PressFileParser;
use roniestein\Press\Repositories\PostRepository;
use roniestein\Press\Tag;

class SavePostsTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_post_can_be_saved(): void
    {
        
        $posts = $this->mockFetchPosts(__DIR__ . '/../blogs/');
        foreach ($posts as $post) {
            (new PostRepository)->save($post);
        }
        
        $this->assertCount(1, Post::all());
        $this->assertDatabaseHas('press_posts',
            [
                'id' => 1,
                'title' => 'My Title',
                "identifier" => "markfile1md",
                "slug" => "my-title",
                "body" => "<h1>Heading</h1>\n<p>Blog post body here</p>",
                "extra" => "{\"description\":\"Description here\"}",
                "published_at" => "2020-02-01 00:00:00",
            ]
        );
    }
    
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
    
    /**
     * Test Driver, to get an array of posts located at a path
     *
     * @param $path
     *
     * @return mixed
     */
    private function mockFetchPosts($path)
    {
        
        $files = File::files($path);
        
        foreach ($files as $file) {
            $this->mockParse($file->getPathname(), $file->getFilename());
        }
        
        return $this->posts;
    }
    
    protected function mockParse($content, $identifier)
    {
        $this->posts[] = array_merge(
            (new PressFileParser($content))->getData(),
            ['identifier' => Str::slug($identifier)]
        );
    }
}