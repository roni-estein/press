<?php

namespace RoniEstein\Press\Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RoniEstein\Press\PressFileParser;

class PressFileParserTest extends TestCase
{
    use RefreshDatabase;
    
    protected $author;
    
    public function setUp(): void
    {
        parent::setUp();
        
        // an author is required to exist in the database for every possible blog post test
        // except failure test
        
        $this->author = factory('RoniEstein\Press\Author')->create(['slug' => 'juan-valdez']);
        
    }
    
    
    /** @test */
    public function two_additional_fields_are_put_into_extra()
    {
        $pressFileParser = (new PressFileParser("---\nread-time: 5 min\nimage: some/image.jpg\n---\n"));
        
        $data = $pressFileParser->getData();
        
        $this->assertEquals(json_encode(['read-time' => '5 min', 'image' => 'some/image.jpg']), $data['extra']);
        
    }
}