<?php

namespace Tests\Feature;

use Tests\TestCase;

class PostTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_create_a_post()
    {

        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');

        $thread = factory('App\Models\Thread')->create([
            'channel_id' => 1,
            'user_id' => $user->id,
        ]);
        $user2 = factory('App\Models\User')->create();
        $this->actingAs($user2, 'api');

        $post_data=[
            'type' => 'post',
            'body' => '首先是饥荒，接着是劳苦和疾病，争执和创伤，还有破天荒可怕的死亡；他颠倒着季侯的次序，轮流地降下了，狂雪和猛火，把那些无遮无盖的人们',
            'brief' => '首先是饥荒，接着是劳苦和疾病，争执和创伤',
        ];
        $response = $this->post('api/thread/'.$thread->id.'/post/', $post_data)
        ->assertStatus(200);

        $response = $this->post('api/thread/'.$thread->id.'/post/', $post_data)
        ->assertStatus(409);

    }

    //还差update test等等……

    /** @test */
    public function an_authorised_user_can_update_own_post()
    {

        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');

        $thread = factory('App\Models\Thread')->create([
            'channel_id' => 1,
            'user_id' => $user->id,
        ]);
        $user2 = factory('App\Models\User')->create();
        $this->actingAs($user2, 'api');

        $post_data=[
            'type' => 'post',
            'body' => '首先是饥荒，接着是劳苦和疾病，争执和创伤，还有破天荒可怕的死亡；他颠倒着季侯的次序，轮流地降下了，狂雪和猛火，把那些无遮无盖的人们',
            'brief' => '首先是饥荒，接着是劳苦和疾病，争执和创伤',
        ];
        $response = $this->post('api/thread/'.$thread->id.'/post/', $post_data)
        ->assertStatus(200);

        $content = $response->decodeResponseJson();

        $data = [
            'title' => 'modifiedchapt',
            'brief' => 'modifiedchapt',
            'body' => 'modifiedchapt',
            'type' => 'chapter',
            'annotation' => 'modified_annotation',
            'warning' => 'modified_warning',
        ];

        $response = $this->patch('api/post/'.$content['data']['id'], $data)

        ->assertStatus(200);

    }
}
