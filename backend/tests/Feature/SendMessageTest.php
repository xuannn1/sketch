<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SendMessageTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_send_message()
    {
        $poster = $this->actingAs(factory('App\Models\User')->create(), 'api');
        $receiver = factory('App\Models\User')->create();

        $body = 'testtesttest';
        $request = $this->post('/api/message/store/'.$receiver->id, ['body' => $body]);

        $response = $request->send();

        $this->assertEquals(200, $response->getStatusCode());
    }
}
