<?php

use RoniEstein\Press\Post;
use RoniEstein\Press\Repositories\PostRepository;
use RoniEstein\Press\Tag;
use RoniEstein\Press\Tests\TestCase;
use function Pest\Laravel\assertDatabaseHas;

beforeEach(fn () => factory('RoniEstein\Press\Author')->create([ 'slug' => 'juan-valdez' ]));

test('a post can be saved', function () {
    
    $posts = TestCase::mockFetchPosts(__DIR__ . '/../scenerios/blog-without-tags');
    
    foreach ( $posts as $post ) {
        ( new PostRepository )->save($post);
    }
    
    expect(Post::all())->toHaveCount(1);
    
    assertDatabaseHas('press_posts',
        [
            'id'           => 1,
            'title'        => 'My Title',
            "identifier"   => "markfile1md",
            "description"  => "Description here",
            "slug"         => "my-title",
            "body"         => "<h1>Heading</h1>\n<p>Blog post body here</p>",
            "extra"        => "{\"chicken\":\"soup\"}",
            "published_at" => null,
        ]
    );
    
    expect(Post::first()->authors)->toHaveCount(1);
});


test('tags will be save when a post is created', function () {
    $posts = TestCase::mockFetchPosts(__DIR__ . '/../scenerios/blog-with-tags');
    foreach ( $posts as $post ) {
        ( new PostRepository )->save($post);
    }
    
    expect(Post::all())->toHaveCount(1);
    assertDatabaseHas('press_posts',
        [
            'id'           => 1,
            'title'        => 'My Title',
            "identifier"   => "markfile1md",
            "description"  => "Description here",
            "slug"         => "my-title",
            "body"         => "<h1>Heading</h1>\n<p>Blog post body here</p>",
            "extra"        => "{\"chicken\":\"soup\"}",
            "published_at" => null,
        ]
    );
    
    
    expect(Tag::all())->toHaveCount(4);
    expect(Post::first()->tags)->toHaveCount(4);
    
});


test('duplicate tags will not be saved twice', function () {
    Tag::create([ 'text' => 'chicken', 'slug' => 'chicken' ]);
    $this->assertCount(1, Tag::all());
    
    $posts = TestCase::mockFetchPosts(__DIR__ . '/../scenerios/blog-with-tags');
    foreach ( $posts as $post ) {
        ( new PostRepository )->save($post);
    }
    
    expect(Post::all())->toHaveCount(1);
    assertDatabaseHas('press_posts',
        [
            'id'           => 1,
            'title'        => 'My Title',
            "identifier"   => "markfile1md",
            "description"  => "Description here",
            "slug"         => "my-title",
            "body"         => "<h1>Heading</h1>\n<p>Blog post body here</p>",
            "extra"        => "{\"chicken\":\"soup\"}",
            "published_at" => null,
        ]
    );
    
    expect(Tag::all())->toHaveCount(4);
    expect(Post::first()->tags)->toHaveCount(4);
});


test('updated markup will update the the stored html', function () {
    
    ( new PostRepository )->save(
        TestCase::mockFetchLatestPost(__DIR__ . '/../scenerios/blog-with-tags')
    );
//    dd(Post::first()->tags);
    expect(Post::first()->tags)->toHaveCount(4);
    
    
    ( new PostRepository )->save(
        TestCase::mockFetchLatestPost(__DIR__ . '/../scenerios/revised-same-blog-with-different-tags')
    );
    
    expect(Post::first()->tags)->toHaveCount(1);


//    $posts = TestCase::mockFetchPosts(__DIR__ . '/../scenerios/revised-blog-with-tags');
//
//    TestCase::clearPosts();
//    foreach ($posts as $post) {
//        (new PostRepository)->save($post);
//    }
//    expect(Post::first()->tags)->toHaveCount(1);
//
//    $posts = TestCase::mockFetchPosts(__DIR__ . '/../scenerios/blog-without-tags');
//
//    TestCase::clearPosts();
//    foreach ($posts as $post) {
//        (new PostRepository)->save($post);
//    }
//    expect(Post::first()->tags)->toHaveCount(0);
});


//test('tags will be removed from post if removed from markup', function() {
//    $posts = TestCase::mockFetchPosts(__DIR__ . '/../scenerios/blog-with-tags');
//
//    foreach ($posts as $post) {
//
//        (new PostRepository)->save($post);
//    }
//
//    expect(Post::first()->tags)->toHaveCount(4);
//
//    $posts = TestCase::mockFetchPosts(__DIR__ . '/../scenerios/revised-blog-with-tags');
//
//    TestCase::clearPosts();
//    foreach ($posts as $post) {
//        (new PostRepository)->save($post);
//    }
//    expect(Post::first()->tags)->toHaveCount(1);
//
//    $posts = TestCase::mockFetchPosts(__DIR__ . '/../scenerios/blog-without-tags');
//
//    TestCase::clearPosts();
//    foreach ($posts as $post) {
//        (new PostRepository)->save($post);
//    }
//    expect(Post::first()->tags)->toHaveCount(0);
//});


test('a post with a header image can be processed', function () {
    $posts = TestCase::mockFetchPosts(__DIR__ . '/../scenerios/blog-with-header-image');
    foreach ( $posts as $post ) {
        ( new PostRepository )->save($post);
    }
    assertDatabaseHas('press_posts',
        [
            'id'           => 1,
            'title'        => 'My Title',
            "identifier"   => "markfile1md",
            "description"  => "Description here",
            "slug"         => "my-title",
            "body"         => "<h1>Heading</h1>\n<p>Blog post body here</p>",
            "extra"        => "{\"header-image\":\"test-image.jpg\",\"header-image-alt\":\"Jelly Beans\",\"header-image-photographer\":\"Photo Bob\",\"chicken\":\"soup\"}",
            "published_at" => null,
        ]);
});

it('throws an error when there is an unknown author', function () {
    
    factory('RoniEstein\Press\Author')->create([ 'slug' => 'mr-coffee' ]);
    
    $this->expectException(\Exception::class);
    $posts = TestCase::mockFetchPosts(__DIR__ . '/../scenerios/blog-with-an-extra-unknown-author');
});


test('a post requires at least one author', function () {
    $this->expectException(\Exception::class);
    $posts = TestCase::mockFetchPosts(__DIR__ . '/../scenerios/blog-without-author');
    foreach ( $posts as $post ) {
        ( new PostRepository )->save($post);
    }
});


it('saves a post with two authors', function () {
    
    factory('RoniEstein\Press\Author')->create([ 'slug' => 'mr-coffee' ]);
    
    $posts = TestCase::mockFetchPosts(__DIR__ . '/../scenerios/blog-with-two-authors');
    
    foreach ( $posts as $post ) {
        ( new PostRepository )->save($post);
    }
    
    expect(Post::all())->toHaveCount(1);
    
    
    assertDatabaseHas('press_posts',
        [
            'id'           => 1,
            'title'        => 'My Title',
            "identifier"   => "markfile1md",
            "description"  => "Description here",
            "slug"         => "my-title",
            "body"         => "<h1>Heading</h1>\n<p>Blog post body here</p>",
            "extra"        => "{\"chicken\":\"soup\"}",
            "published_at" => null,
        ]
    );
    expect(Post::first()->authors)->toHaveCount(2);
    
});

test('a header image is properly parsed', function () {
    
    ( new PostRepository )->save(
//        TestCase::mockFetchLatestPost(__DIR__ . '/../scenerios/blog-with-header-image')
        TestCase::mockFetchLatestPost(__DIR__ . '/../scenerios/blog-with-url-header-image')
    );
    
    $post = Post::first();
    
    expect($post->extra)
        ->json()
        ->toHaveCount(4)
        ->sequence(
            fn ($value) => $value->toEqual('https://munroepharmacy.com/img/blog/FWQ3Pj6y5jV7DQlltDqh1urmWYipqdj7IQmAnvPg.jpeg'),
            fn ($value) => $value->toEqual('Punches'),
            fn ($value) => $value->toEqual('Mr. Photographer'),
            fn ($value) => $value->toEqual('soup'),
        );
    
});