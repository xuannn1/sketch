<?php

namespace Tests\Feature;

use Tests\TestCase;
use DB;

class MessageTest extends TestCase
{
    /** @test */
    public function an_authorised_user_can_send_message()//登陆用户可发私信
    {
        $poster = factory('App\Models\User');
        $receiver = factory('App\Models\User')->create();
        $this->actingAs($poster, 'api');

        $body = 'send this message';
        $request = $this->post('/api/messages', ['sendTo' => $receiver->id, 'body' => $body])
        ->assertStatus(200)
        ->assertJsonStructure([
            'code',
            'data' => [
              "poster_id",
              "receiver_id",
              "message_body_id",
              "created_at",
              "id",
            ],
        ])
        ->assertJson([
            'code' => 200,
            'data' => [
              "poster_id" => $poster->id,
              "receiver_id" => $receiver->id,
            ],
        ]);
        $this->assertEquals(0, $poster->message_limit);
    }

    /** @test */
    public function a_guest_can_not_send_message()//游客不可发私信
    {
        $receiver = factory('App\Models\User')->create();
        $body = 'send this message';

        $request = $this->post('/api/messages', ['sendTo' => $receiver->id, 'body' => $body])->assertStatus(401);
    }

    /** @test */
    public function an_authorised_user_has_no_message_limit_can_not_send_message()//没有message_limit的用户不可发私信
    {
        $poster = factory('App\Models\User')->create();
        $receiver = factory('App\Models\User')->create();
        $this->actingAs($poster, 'api');
        $body = 'send this message';

        $request = $this->post('/api/messages', ['sendTo' => $receiver->id, 'body' => $body])->assertStatus(403);
    }

    /** @test */
    public function can_not_send_message_to_the_user_who_set_no_stranger_message()//不可发私信给设置了no_stranger_message的用户
    {
        $poster = factory('App\Models\User')->create(['message_limit' => 1]);
        $receiver = factory('App\Models\User')->create(['no_stranger_message' => 1]);
        $this->actingAs($poster, 'api');
        $body = 'send this message';

        $request = $this->post('/api/messages', ['sendTo' => $receiver->id, 'body' => $body])->assertStatus(403);
    }

    /** @test */
    public function user_can_not_send_message_to_himself()//用户不可以给自己发私信
    {
        $poster = factory('App\Models\User')->create(['message_limit' => 1]);
        $this->actingAs($poster, 'api');
        $body = 'send this message';

        $request = $this->post('/api/messages', ['sendTo' => $poster->id, 'body' => $body])->assertStatus(403);
    }

    /** @test */
    public function an_authorised_user_can_check_messages_received()//登陆用户可查看已收私信
    {
        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');
        $message = factory('App\Models\Message')->create(['receiver_id' => $user->id]);

        $request = $this->get('/api/user/'.$user->id.'/messages?withStyle=receivebox')
        ->assertStatus(200)
        ->assertJsonStructure([
            'code',
            'data' => [
                'receivebox' => [[
                    'type',
                    'id',
                    'attributes' => [
                        'poster_id',
                        'receiver_id',
                        'message_body' => [
                            'id',
                            'body',
                        ],
                        'seen',
                    ],
                    'poster',
                    'receiver',
                ]],
            ],
        ])
        ->assertJson([
            'code' => 200,
            'data' => [
                'receivebox' => [[
                      'type' => 'Message',
                      'id' => $message->id,
                      'attributes' => [
                          'poster_id' => $message->poster->id,
                          'receiver_id' => $user->id,
                          'message_body' => [
                              'id' => $message->body->id,
                              'body' => $message->body->body,
                          ],
                          'seen' => 0,
                      ],
                  ]],
            ],
        ]);
    }

    /** @test */
    public function administrator_can_check_all_messages_received()//管理员可查看用户已收私信
    {
        $user = factory('App\Models\User')->create();
        $admin = factory('App\Models\User')->create();
        DB::table('role_user')->insert([
            'user_id' => $admin->id,
            'role' => 'admin'
        ]);
        $message = factory('App\Models\Message')->create(['receiver_id' => $user->id]);
        $this->actingAs($admin, 'api');

        $request = $this->get('/api/user/'.$user->id.'/messages?withStyle=receivebox')
        ->assertStatus(200)
        ->assertJsonStructure([
            'code',
            'data' => [
                'receivebox' => [[
                    'type',
                    'id',
                    'attributes' => [
                        'poster_id',
                        'receiver_id',
                        'message_body' => [
                            'id',
                            'body',
                        ],
                        'seen',
                    ],
                    'poster',
                    'receiver',
                ]],
            ],
        ])
        ->assertJson([
            'code' => 200,
            'data' => [
                'receivebox' => [[
                      'type' => 'Message',
                      'id' => $message->id,
                      'attributes' => [
                          'poster_id' => $message->poster->id,
                          'receiver_id' => $user->id,
                          'message_body' => [
                              'id' => $message->body->id,
                              'body' => $message->body->body,
                          ],
                          'seen' => 0,
                      ],
                  ]],
            ],
        ]);
    }

    /** @test */
    public function a_guest_user_can_not_check_messages_received()//游客不可查看已收私信
    {
        $user = factory('App\Models\User')->create();

        $request = $this->get('/api/user/'.$user->id.'/messages?withStyle=receivebox')->assertStatus(401);
    }

    /** @test */
    public function an_authorised_user_can_check_messages_sent()//登陆用户可查看已发私信
    {
        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');
        $message = factory('App\Models\Message')->create(['poster_id' => $user->id]);

        $request = $this->get('/api/user/'.$user->id.'/messages?withStyle=sendbox')
        ->assertStatus(200)
        ->assertJsonStructure([
            'code',
            'data' => [
                'sendbox' => [[
                    'type',
                    'id',
                    'attributes' => [
                        'poster_id',
                        'receiver_id',
                        'message_body' => [
                            'id',
                            'body',
                        ],
                        'seen',
                    ],
                    'poster',
                    'receiver',
                ]],
            ],
        ])
        ->assertJson([
            'code' => 200,
            'data' => [
                'sendbox' => [[
                      'type' => 'Message',
                      'id' => $message->id,
                      'attributes' => [
                          'poster_id' => $user->id,
                          'receiver_id' => $message->receiver->id,
                          'message_body' => [
                              'id' => $message->body->id,
                              'body' => $message->body->body,
                          ],
                      ],
                  ]],
            ],
        ]);
    }

    /** @test */
    public function administrator_can_check_all_message_sent()//管理员可查看用户已发私信
    {
        $user = factory('App\Models\User')->create();
        $admin = factory('App\Models\User')->create();
        DB::table('role_user')->insert([
            'user_id' => $admin->id,
            'role' => 'admin'
        ]);
        $this->actingAs($admin, 'api');
        $message = factory('App\Models\Message')->create(['poster_id' => $user->id]);

        $request = $this->get('/api/user/'.$user->id.'/messages?withStyle=sendbox')
        ->assertStatus(200)
        ->assertJsonStructure([
            'code',
            'data' => [
                'sendbox' => [[
                    'type',
                    'id',
                    'attributes' => [
                        'poster_id',
                        'receiver_id',
                        'message_body' => [
                            'id',
                            'body',
                        ],
                        'seen',
                    ],
                    'poster',
                    'receiver',
                ]],
            ],
        ])
        ->assertJson([
            'code' => 200,
            'data' => [
                'sendbox' => [[
                      'type' => 'Message',
                      'id' => $message->id,
                      'attributes' => [
                          'poster_id' => $user->id,
                          'receiver_id' => $message->receiver->id,
                          'message_body' => [
                              'id' => $message->body->id,
                              'body' => $message->body->body,
                          ],
                      ],
                  ]],
            ],
        ]);
    }

    /** @test */
    public function a_guest_user_can_not_check_messages_sent()//游客不可查看已发私信
    {
        $user = factory('App\Models\User')->create();

        $request = $this->get('/api/user/'.$user->id.'/messages?withStyle=sendbox')->assertStatus(401);
    }

    /** @test */
    public function an_authorised_user_can_check_dialogue()//登陆用户可查看与另一用户的对话
    {
        $user = factory('App\Models\User')->create();
        $chatWith = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');
        $message = factory('App\Models\Message')->create(['poster_id' => $user->id, 'receiver_id' => $chatWith->id]);

        $request = $this->get('/api/user/'.$user->id.'/messages?withStyle=dialogue&chatWith='.$chatWith->id)
        ->assertStatus(200)
        ->assertJsonStructure([
            'code',
            'data' => [
                'dialogue' => [[
                    'type',
                    'id',
                    'attributes' => [
                        'poster_id',
                        'receiver_id',
                        'message_body' => [
                            'id',
                            'body',
                        ],
                        'seen',
                    ],
                    'poster',
                    'receiver',
                ]],
            ],
        ])
        ->assertJson([
            'code' => 200,
            'data' => [
                'dialogue' => [[
                      'type' => 'Message',
                      'id' => $message->id,
                      'attributes' => [
                          'poster_id' => $user->id,
                          'receiver_id' => $chatWith->id,
                          'message_body' => [
                              'id' => $message->body->id,
                              'body' => $message->body->body,
                          ],
                      ],
                  ]],
            ],
        ]);
    }

    /** @test */
    public function a_user_can_not_check_dialogue_when_do_not_input_chatWith()//当未输入chatWith时返回错误
    {
        $user = factory('App\Models\User')->create();
        $this->actingAs($user, 'api');

        $request = $this->get('/api/user/'.$user->id.'/messages?withStyle=dialogue')->assertStatus(422);
    }

    /** @test */
    public function administrator_can_check_dialogue()//管理员可查看用户1和用户2之间的对话
    {
        $user = factory('App\Models\User')->create();
        $admin = factory('App\Models\User')->create();
        DB::table('role_user')->insert([
            'user_id' => $admin->id,
            'role' => 'admin'
        ]);
        $chatWith = factory('App\Models\User')->create();
        $this->actingAs($admin, 'api');
        $message = factory('App\Models\Message')->create(['poster_id' => $user->id, 'receiver_id' => $chatWith->id]);

        $request = $this->get('/api/user/'.$user->id.'/messages?withStyle=dialogue&chatWith='.$chatWith->id)
        ->assertStatus(200)
        ->assertJsonStructure([
            'code',
            'data' => [
                'dialogue' => [[
                    'type',
                    'id',
                    'attributes' => [
                        'poster_id',
                        'receiver_id',
                        'message_body' => [
                            'id',
                            'body',
                        ],
                        'seen',
                    ],
                    'poster',
                    'receiver',
                ]],
            ],
        ])
        ->assertJson([
            'code' => 200,
            'data' => [
                'dialogue' => [[
                      'type' => 'Message',
                      'id' => $message->id,
                      'attributes' => [
                          'poster_id' => $user->id,
                          'receiver_id' => $chatWith->id,
                          'message_body' => [
                              'id' => $message->body->id,
                              'body' => $message->body->body,
                          ],
                      ],
                  ]],
            ],
        ]);
    }

    /** @test */
    public function a_guest_user_can_not_check_dialogue()//游客不可查看对话
    {
        $user = factory('App\Models\User')->create();
        $chatWith = factory('App\Models\User')->create();

        $request = $this->get('/api/user/'.$user->id.'/messages?withStyle=dialogue&chatWith='.$chatWith->id)->assertStatus(401);
    }
}
