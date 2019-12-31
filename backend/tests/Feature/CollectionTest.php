<?php

namespace Tests\Feature;

use Tests\TestCase;

class CollectionTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_collect_a_thread_and_update_and_delete_it()
    {

        $author = factory('App\Models\User')->create();

        $thread = factory('App\Models\Thread')->create([
            'channel_id' => 1,
            'user_id' => $author->id,
            'is_public' => true,
        ]);

        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');
        //增
        $response = $this->post('api/thread/'.$thread->id.'/collect')
        ->assertJson([
            'code' => 200,
            'data' => [
                'type' => 'collection',
                'attributes' => [
                    'user_id' => $user->id,
                    'thread_id' => $thread->id,
                ],
            ],
        ]);
        $content = $response->decodeResponseJson();
        $data = [
            'keep_updated' => false,
        ];
        $response = $this->patch('api/collection/'.$content['data']['id'], $data)
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'attributes' => $data,
            ],
        ]);
        $data = [
            'keep_updated' => true,
        ];
        $response = $this->patch('api/collection/'.$content['data']['id'], $data)
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'attributes' => $data,
            ],
        ]);
        $content = $response->decodeResponseJson();
        $response = $this->delete('api/collection/'.$content['data']['id'], $data)
        ->assertStatus(200)
        ->assertJson([
            'data' => 'deleted',
        ]);
    }

    /** @test */
    public function an_authorised_user_can_see_his_collections()
    {
        $author = \App\Models\User::inRandomOrder()->first();

        $thread1 = factory('App\Models\Thread')->create([
            'channel_id' => 1,
            'user_id' => $author->id,
            'is_public' => true,
        ]);
        $thread2 = factory('App\Models\Thread')->create([
            'channel_id' => 4,
            'user_id' => $author->id,
            'is_public' => true,
        ]);

        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');
        //增
        $response = $this->post('api/thread/'.$thread1->id.'/collect');
        $response = $this->post('api/thread/'.$thread2->id.'/collect');
        $response = $this->get('api/user/'.$user->id.'/collection')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'threads',
                'paginate',
            ],
        ]);
        // $content = $response->decodeResponseJson();
        // dd($content);

        $data = [
            'withType' => 'thread',
        ];
        $response = $this->get('api/user/'.$user->id.'/collection', $data)
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'threads',
                'paginate',
            ],
        ]);
        // $content = $response->decodeResponseJson();
        // dd($content);

    }

}
