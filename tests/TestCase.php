<?php

namespace RoniEstein\Press\Tests;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Testing\Assert as PHPUnit;
use RoniEstein\Press\PressBaseServiceProvider;
use RoniEstein\Press\PressFileParser;

class TestCase extends \Orchestra\Testbench\TestCase
{
    
    public static array $posts = [];
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->withFactories(__DIR__ . '/../database/factories');
        $this->withFactories(__DIR__ . '/../tests/database/factories');
        
        static::clearPosts();
    }
    
    /**
     * Get the current posts that have been processed
     *
     * @return array
     */
    public static function posts()
    {
        return self::$posts;
    }
    /**
     * Test Driver, to get an array of posts located at a path
     * @param $path
     *
     * @return array
     */
    public static function mockFetchLatestPost($path): array
    {
        $files = File::files($path);
        foreach ($files as $file) {
            static::mockParse($file->getPathname(), $file->getFilename());
        }
        
        return Arr::last(self::$posts);
    }
    
    /**
     * Test Driver, to get an array of posts located at a path
     * @param $path
     *
     * @return mixed
     */
    public static function mockFetchPosts($path)
    {
        $files = File::files($path);
        
        foreach ($files as $file) {
            static::mockParse($file->getPathname(), $file->getFilename());
        }
        
        return self::$posts;
    }
    
    /**
     * @param $content
     * @param $identifier
     *
     */
    public static function mockParse($content, $identifier)
    {
        
        self::$posts[Str::slug($identifier)] = array_merge(
            (new PressFileParser($content))->getData(),
            ['identifier' => Str::slug($identifier)]
        );
    }
    
    public static function clearPosts(): void
    {
        self::$posts = [];
    }
    
    
    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            PressBaseServiceProvider::class,
        ];
    }
    
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testdb');
        $app['config']->set('database.connections.testdb', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }
    
    public function assertJsonSubset($expected, $actual, $strict = false)
    {
        
        if (!is_array($expected)) {
            $expected = $this->decodeJson($expected);
        }
        if (count($expected) === 0) {
            $this->fail('Failure: $expected dataset cannot be empty');
        }
        
        if (!is_array($actual)) {
            $actual = $this->decodeJson($actual);
        }
        
        if (count($expected) === 0) {
            $this->fail('$actual dataset was empty');
        }
        
        
        PHPUnit::assertArraySubset($expected, $actual, $strict,
            $this->assertJsonMessage($expected, $actual));
        
        return $this;
    }
    
    public function decodeJson($data, $key = null)
    {
        $decodedJson = json_decode($data, true);
        
        return data_get($decodedJson, $key);
    }
    
    public function assertJsonMessage(array $expected, array $actual)
    {
        $expected = json_encode($expected, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        $actual = json_encode($actual, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        return 'Unable to find JSON: ' . PHP_EOL . PHP_EOL .
            "[{$expected}]" . PHP_EOL . PHP_EOL .
            'within response JSON:' . PHP_EOL . PHP_EOL .
            "[{$actual}]." . PHP_EOL . PHP_EOL;
    }
    
}