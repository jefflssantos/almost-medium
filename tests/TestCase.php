<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh');

        $this->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);
    }
}
