<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class RewardTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function a_user_can_create_reward()
    {
        
        
        $user=factory('App\Models\User')->create();
        $userinfo = factory('App\Models\UserInfo')->create(['user_id' => $user->id]);
        $this->actingAs($user, 'api');
        $thread = factory('App\Models\Thread')->create([
            'channel_id' => 1,
            'user_id' => $user->id,
        ]);

        $post = factory('App\Models\Post')->create([
            'thread_id' => $thread->id,
            'user_id' => $user->id,
        ]);

        $data = [
            'rewardable_type' => 'Thread',
            'rewardable_id' => $thread->id,
            'attribute' => 'sangdian',
            'value' => 3,
        ];

        $response = $this->post('api/reward', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('rewards',$data);

        $response = $this->post('api/reward', $data);
        $response->assertStatus(409);//重复打赏

        $data = [
            'rewardable_type' => 'Post',
            'rewardable_id' => $post->id,
            'attribute' => 'xianyu',
            'value' => 5,
        ];

        $response = $this->post('api/reward', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('rewards',$data);

        $data = [
            'rewardable_type' => 'Post',
            'rewardable_id' => '0',
            'attribute' => 'sangdian',
            'value' => 1,
        ];

        $response = $this->post('api/reward', $data);
        $response->assertStatus(404);

        $response = $this->get('api/reward?rewardable_type=Thread&rewardable_id='.$thread->id);
        $response->assertStatus(200);
    }

    /** @test */
    public function a_user_can_cancel_reward(){
        $user=factory('App\Models\User')->create();
        $userinfo = factory('App\Models\UserInfo')->create(['user_id' => $user->id]);
        $this->actingAs($user, 'api');
        $thread = factory('App\Models\Thread')->create([
            'channel_id' => 1,
            'user_id' => $user->id,
        ]);

        $post = factory('App\Models\Post')->create([
            'thread_id' => $thread->id,
            'user_id' => $user->id,
        ]);

        $data = [
            'rewardable_type' => 'Thread',
            'rewardable_id' => $thread->id,
            'attribute' => 'shengfan',
            'value' => 3,
        ];

        $response = $this->post('api/reward', $data);
        //dd($response);
        $response->assertStatus(200);
        $this->assertDatabaseHas('rewards',$data);

        $content = $response->decodeResponseJson();
        $response = $this->delete('api/reward/'.$content['data']['id']);
        //dd($response);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('rewards',$data);

    }
    
}
