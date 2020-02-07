<?php

namespace roniestein\Press\Tests;

use roniestein\Press\PressBaseServiceProvider;
use Illuminate\Foundation\Testing\Assert as PHPUnit;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../database/factories');
        $this->withFactories(__DIR__.'/../tests/database/factories');
        
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
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
            'database' => ':memory:'
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