<?php

namespace Tests\Feature;

use Tests\TestCase;

class ReviewTest extends TestCase
{

    /**
    * A basic test example.
    *
    * @return void
    */

    private function createThread($user, $type){
        $channel = collect(config('channel'))->where('type','list')->first();
        $thread = factory('App\Models\Thread')->create([
            'channel_id' => $channel->id,
            'user_id' => $user->id,
        ]);
        return $thread;
    }

    /** @test */
    public function list_owner_can_add_new_review()
    {
        $author = factory('App\Models\User')->create();
        $reviewee = $this->createThread($author, 'book');
        $user = factory('App\Models\User')->create();
        $list = $this->createThread($user, 'list');
        $this->actingAs($user, 'api');
        $data = [
            'reviewee_id' => $reviewee->id,
            'title' => '评《xxx》:根本不好看',
            'brief' => 'sdalkenfaifoub',
            'body'=> '是人性的堕落还是丧失？',
            'use_markdown' => true,
            'use_indentation' => false,
            'recommend' => false,
            'rating' => 3,
        ];
        $response = $this->post('api/thread/'.$list->id.'/review', $data)
        ->assertStatus(200)
        ->assertJson([
            'code' => 200,
            'data' => [
                'type' => 'post',
                'attributes' => [
                    'title' => $data['title'],
                    'body' => $data['body'],
                    'brief' => $data['brief'],
                    //'use_markdown' => $data['use_markdown'],
                    'use_indentation' => $data['use_indentation'],
                ],
                'review' => [
                    'type' => 'review',
                    'attributes' => [
                        'thread_id' => $reviewee->id,
                        'recommend' => $data['recommend'],
                        'rating' => $data['rating'],
                    ],
                    'reviewee' => [
                        'type' => 'thread',
                        'id' => $reviewee->id,
                        'attributes' => [
                            'title' => $reviewee->title,
                            'is_anonymous' => (bool)$reviewee->is_anonymous,
                            'majia' =>  (string)$reviewee->majia,
                        ],
                    ],
                ],
            ],
        ]);

        $data = [
            'reviewee_id' => $reviewee->id,
            'title' => '评《xxx》:好看极了',
            'brief' => '改改sdalkenfaifoub',
            'body'=> '改改是人性的堕落还是丧失？',
            'use_markdown' => true,
            'use_indentation' => false,
            'recommend' => true,
            'rating' => 3,
        ];
        $content = $response->decodeResponseJson();
        $response = $this->patch('api/thread/'.$list->id.'/review/'.$content['data']['id'], $data)
        ->assertStatus(200)
        ->assertJson([
            'code' => 200,
            'data' => [
                'type' => 'post',
                'attributes' => [
                    'title' => $data['title'],
                    'body' => $data['body'],
                    'brief' => $data['brief'],
                    //'use_markdown' => $data['use_markdown'],
                    'use_indentation' => $data['use_indentation'],
                ],
                'review' => [
                    'type' => 'review',
                    'attributes' => [
                        'thread_id' => $reviewee->id,
                        'recommend' => $data['recommend'],
                        'rating' => $data['rating'],
                    ],
                ],
            ],
        ]);
    }
}
