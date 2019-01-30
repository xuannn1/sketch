<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use WithFaker;
    //以下代码，如果不想在test前后重置database的话，可以屏蔽掉。如果想要每次重值database，就把它释放出来
    // use RefreshDatabase;
    //
    // public function setUp()
    // {
    //     parent::setUp();
    //
    //     $this->artisan('db:seed');
    //     $this->artisan('passport:install');
    // }
    //以下代码，如果不想在test前后重置database的话，可以屏蔽掉。如果想要每次重值database，就把它释放出来
}
