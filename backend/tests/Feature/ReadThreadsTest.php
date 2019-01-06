<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Thread;

class ReadThreadsTest extends TestCase
{
    protected $thread;

    public function setUp(){
        parent::setUp();
        $this->thread = Thread::find(1);
    }

    /** @test */
    public function a_thread_can_add_a_post()
    {
        $response = $this->get('/api/thread/1/');

        $response->assertStatus(200);



    }
}
