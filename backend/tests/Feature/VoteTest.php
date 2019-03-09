<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class VoteTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use DatabaseTransactions;
    public function testa_user_can_create_vote()
    {
        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');
        $quote = factory('App\Models\Quote')->create(['user_id' => $user->id]);

        $data = [
            'votable_type' => 'Quote',
            'votable_id' => $quote->id,
            'attitude' => 'upvote',
        ];

        $response = $this->post('api/vote', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('votes',$data);

        $response = $this->post('api/vote', $data);
        $response->assertStatus(409);//重复投票

        $data = [
            'votable_type' => 'Quote',
            'votable_id' => $quote->id,
            'attitude' => 'downvote',
        ];

        $response = $this->post('api/vote', $data);
        $response->assertStatus(409);//检查无效投票
        $this->assertDatabaseMissing('votes',$data);

        $data = [
            'votable_type' => 'Status',
            'votable_id' => '0',
            'attitude' => 'upvote',
        ];

        $response = $this->post('api/vote', $data);
        //dd($response);
        $response->assertStatus(404);
        $response = $this->get('api/vote?votable_type=Status&votable_id=2');
        $response->assertStatus(200);
        //$response->assertStatus(200);
    }
}
