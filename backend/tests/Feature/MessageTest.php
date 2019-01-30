<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class MessageTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_send_message()
    {
        $receiver = factory('App\Models\User')->create();
        $poster = factory('App\Models\User')->create();
        $this->actingAs($poster, 'api');

        $body = 'send this message';
        $request = $this->post('/api/message/store/'.$receiver->id, ['body' => $body])
        ->assertStatus(200);
    }
}
