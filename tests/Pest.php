<?php

use Carbon\Carbon;
use RoniEstein\Press\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class)->in('Feature', 'Unit');
uses(RefreshDatabase::class)->in('Feature');


function ddf($args){
    dd($args);
}