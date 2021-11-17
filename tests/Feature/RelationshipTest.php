<?php

use RoniEstein\Press\Author;
use RoniEstein\Press\Post;
use RoniEstein\Press\Tag;

test('a post can be created with the factory', function () {
    factory(Post::class)->create();
    
    expect(Post::all())->toHaveCount(1);
});

test('a post can have many tags', function () {
    $post = factory(Post::class)->create();
    $tags = factory(Tag::class, 2)->create();
    $post->tags()->sync($tags);
    
    expect(Post::all())->toHaveCount(1);
    expect(Tag::all())->toHaveCount(2);
    
});


test('a tag can have many posts', function () {
    $tag   = factory(Tag::class)->create();
    $posts = factory(Post::class, 2)->create();
    $tag->posts()->sync($posts);
    
    expect(Post::all())->toHaveCount(2);
    expect($tag->posts)->toHaveCount(2);
    
});

test('a post has an author', function () {
    $author = factory(Author::class)->create();
    $post   = factory(Post::class)->create();
    
    $post->authors()->sync([
        'press_author_id'   => $author->id,
        'press_author_type' => get_class($author),
    
    ]);
    
    expect($post->authors->first())
        ->toExist()
        ->toBeSameModelAs($author);
    
});

test('an author owns one or more posts', function () {
    $author = factory(Author::class)->create();
    $posts  = factory(Post::class, 2)->raw();
    $author->posts()->createMany($posts);
    
    expect($author->posts)
        ->toHaveCount(2)
        ->sequence(
            fn ($post) => $post->identifier->toEqual($posts[0]['identifier']),
            fn ($post) => $post->identifier->toEqual($posts[1]['identifier']),
        );
});