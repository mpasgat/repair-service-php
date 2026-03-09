<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.key', 'base64:dXW4f3v0eRmK6jTz+de2klN3xzDAJm5V0QhYz7mYxjI=');
    }
}
