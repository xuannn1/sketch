<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class VoteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testa_user_can_create_vote()
    {
        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');
        $quote = factory('App\Models\Quote')->create(['user_id' => $user->id]);
        $data = [
            'votable_type' => 'App\Models\Quote',
            'votable_id' => '1',
            'attitude' => 'upvote',
        ];
        $response = $this->post('api/vote', $data);
        $response->assertStatus(200);
        $response = $this->post('api/vote', $data);
        $response->assertStatus(409);//重复投票

        $data = [
            'votable_type' => 'App\Models\Quote',
            'votable_id' => '1',
            'attitude' => 'downvote',
        ];

        $response = $this->post('api/vote', $data);
        $response->assertStatus(403);//检查无效投票

        $data = [
            'votable_type' => 'App\Models\Status',
            'votable_id' => '41',
            'attitude' => 'upvote',
        ];

        $response = $this->post('api/vote', $data);
        //dd($response);
        $response->assertStatus(410);
        $response = $this->get('api/votes?votable_type=App\Models\Status&votable_id=2');
        $response->assertStatus(200);
        //$response->assertStatus(200);
    }
}
