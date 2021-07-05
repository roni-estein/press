<?php /** @noinspection ALL */


use Carbon\Carbon;
use RoniEstein\Press\MarkdownParser;
use RoniEstein\Press\PressFileParser;

beforeEach(fn () => factory('RoniEstein\Press\Author')->create([ 'slug' => 'juan-valdez' ]));

test('simple markdown is parsed')
    ->expect(MarkdownParser::parse('# Heading'))
    ->toEqual('<h1>Heading</h1>');


test('the head and body gets split', function() {
    
    $pressFileParser = (new PressFileParser(__DIR__ . '/../scenerios/blog-without-tags/MarkFile1.md'));
    
    $data = $pressFileParser->getRawData();
    
    expect($data)
        ->toBeArray()
        ->toHaveCount(3);
    
    expect($data[1])
        ->toContain('title: My Title')
        ->toContain('description: Description here')
        ->not->toContain('Blog post body here');
    
    expect($data[2])
        ->toContain('Blog post body here')
        ->not->toContain('title: My Title')
        ->not->toContain('description: Description here');

});

test('a string can also be used instead', function() {
    
    $pressFileParser = (new PressFileParser("---\ntitle: My Title\n---\nBlog post body here"));
    
    $data = $pressFileParser->getRawData();
    
    expect($data)
        ->toBeArray()
        ->toHaveCount(3);
    
    expect($data[1])
        ->toContain('title: My Title')
        ->not->toContain('Blog post body here');
    
    expect($data[2])
        ->toContain('Blog post body here')
        ->not->toContain('title: My Title');
    
});

test('each_head_field_gets_separated', function() {
    
    $pressFileParser = (new PressFileParser(__DIR__ . '/../scenerios/blog-without-tags/MarkFile1.md'));
    
    $data = $pressFileParser->getData();
    
    expect($data)
        ->title->toContain('My Title')
        ->description->toContain('Description here');
    
});

test('the_body_gets_saved_and_trimmed', function() {
    
    $pressFileParser = (new PressFileParser(__DIR__ . '/../scenerios/blog-without-tags/MarkFile1.md'));
    
    $data = $pressFileParser->getData();
    
    expect($data)
        ->body->toContain("<h1>Heading</h1>\n<p>Blog post body here</p>");
    
});

test('a_date_field_gets_parsed', function() {
    
    $pressFileParser = (new PressFileParser("---\ndate: May 14, 1988\n---\n"));
    
    $data = $pressFileParser->getData();
    
    expect($data)
        ->date->toBeInstanceOf(Carbon::class)
        ->date->toHaveDate('1988-05-14');
    
});

test('an_extra_field_gets_saved', function() {
    
    $pressFileParser = (new PressFileParser("---\nread-time: 5 min\n---\n"));
    
    $data = $pressFileParser->getData();
    
    expect($data)
        ->extra
        ->toBeJson()
        ->json()
        ->toHaveCount(1)
        ->sequence(
            fn($value, $key) => $key->toBe('read-time') && $value->toBe('5 min')
        );
    
});

test('two_additional_fields_are_put_into_extra', function() {
    
    $pressFileParser = (new PressFileParser("---\nread-time: 5 min\nimage: some/image.jpg\n---\n"));
    
    $data = $pressFileParser->getData();
    
    expect($data)
        ->extra
        ->toBeJson()
        ->json()
        ->toHaveCount(2)
        ->sequence(
            fn($value, $key) => $key->toBe('read-time') && $value->toBe('5 min'),
            fn($value, $key) => $key->toBe('image') && $value->toBe('some/image.jpg')
        );
    
});

