<?php

namespace roniestein\Press\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use roniestein\Press\Author;
use roniestein\Press\Post;
use roniestein\Press\PressFileParser;
use roniestein\Press\Repositories\PostRepository;
use roniestein\Press\Tag;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SavePostsTest extends TestCase
{
    use RefreshDatabase;
    
    protected $author;
    
    /** @test */
    public function a_post_can_be_saved(): void
    {
        
        $posts = $this->mockFetchPosts(__DIR__ . '/../scenerios/blog-without-tags');
        foreach ($posts as $post) {
            (new PostRepository)->save($post);
        }
        
        $this->assertCount(1, Post::all());
        $this->assertDatabaseHas('press_posts',
            [
                'id' => 1,
                'title' => 'My Title',
                "identifier" => "markfile1md",
                "description" => "Description here",
                "slug" => "my-title",
                "author_type" => "roniestein\\Press\\Author",
                "author_id" => "1",
                "body" => "<h1>Heading</h1>\n<p>Blog post body here</p>",
                "extra" => "{\"chicken\":\"soup\"}",
                "published_at" => null,
            ]
        );
    }
    
    /** @test */
    public function tags_will_be_saved_when_a_post_is_created(): void
    {
        $posts = $this->mockFetchPosts(__DIR__ . '/../scenerios/blog-with-tags');
        foreach ($posts as $post) {
            (new PostRepository)->save($post);
        }
        
        $this->assertCount(1, Post::all());
        $this->assertDatabaseHas('press_posts',
            [
                'id' => 1,
                'title' => 'My Title',
                "identifier" => "markfile1md",
                "description" => "Description here",
                "author_type" => "roniestein\\Press\\Author",
                "author_id" => "1",
                "slug" => "my-title",
                "body" => "<h1>Heading</h1>\n<p>Blog post body here</p>",
                "extra" => "{\"chicken\":\"soup\"}",
                "published_at" => null,
            ]
        );
        
        $this->assertCount(4, Tag::all());
        $this->assertCount(4, Post::first()->tags);
        
    }
    
    /** @test */
    public function duplicate_tags_will_not_be_saved_twice(): void
    {
        Tag::create(['text' => 'chicken', 'slug' => 'chicken']);
        $this->assertCount(1, Tag::all());
        
        $posts = $this->mockFetchPosts(__DIR__ . '/../scenerios/blog-with-tags');
        foreach ($posts as $post) {
            (new PostRepository)->save($post);
        }
        
        $this->assertCount(1, Post::all());
        $this->assertDatabaseHas('press_posts',
            [
                'id' => 1,
                'title' => 'My Title',
                "identifier" => "markfile1md",
                "description" => "Description here",
                "author_type" => "roniestein\\Press\\Author",
                "author_id" => "1",
                "slug" => "my-title",
                "body" => "<h1>Heading</h1>\n<p>Blog post body here</p>",
                "extra" => "{\"chicken\":\"soup\"}",
                "published_at" => null,
            ]
        );
        
        $this->assertCount(4, Tag::all());
        $this->assertCount(4, Post::first()->tags);
        
    }
    
    /** @test */
    public function tags_will_be_removed_from_post_if_removed_from_markup(): void
    {
        $posts = $this->mockFetchPosts(__DIR__ . '/../scenerios/blog-with-tags');
        
        foreach ($posts as $post) {
            
            (new PostRepository)->save($post);
        }
        
        $this->assertCount(4, Post::first()->tags);
        
        $posts = $this->mockFetchPosts(__DIR__ . '/../scenerios/revised-blog-with-tags');
        
        $this->clearPosts();
        foreach ($posts as $post) {
            (new PostRepository)->save($post);
        }
        $this->assertCount(1, Post::first()->tags);
        
        $posts = $this->mockFetchPosts(__DIR__ . '/../scenerios/blog-without-tags');
        
        $this->clearPosts();
        foreach ($posts as $post) {
            (new PostRepository)->save($post);
        }
        $this->assertCount(0, Post::first()->tags);
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
    
    /** @test */
    public function a_post_has_an_author(): void
    {
        $post = factory(Post::class)->create([
            'author_id' => $this->author->id,
            'author_type' => get_class($this->author),
            
            ]);
        
        $this->assertTrue($post->author->is($this->author));
        
    }
    
    /** @test */
    public function an_author_owns_one_or_more_posts(): void
    {
        $author = $this->author;
        $posts = $author->posts()->createMany(factory(Post::class, 2)->raw());
        
        $this->assertJsonSubset($author->posts, $posts->fresh());
        
    }
    
    /** @test */
    public function a_post_without_a_valid_author_stops_processing_and_throws_an_error(): void
    {
        try {
            $posts = $this->mockFetchPosts(__DIR__ . '/../scenerios/blog-without-author');
            foreach ($posts as $post) {
                (new PostRepository)->save($post);
            }
            $this->fail('No Http exception was thrown when a blog was posted without an author field present');
        }
        catch (HttpException $e){
            //An exception  was thrown
            $this->assertEquals($e->getStatusCode(), 422);
            $this->assertStringContainsString(
                'No author present on the blog post:', $e->getMessage());
            
        }
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
    
    protected function clearPosts(): void
    {
        $this->posts = [];
    }
    
    public function setUp(): void
    {
        parent::setUp();
        
        // an author is required to exist in the database for every possible blog post test
        // except failure test
        
        $this->author = factory('roniestein\Press\Author')->create(['slug' => 'juan-valdez']);
        
    }
}