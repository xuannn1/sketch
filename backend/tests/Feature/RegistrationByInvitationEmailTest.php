<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationByInvitationEmailTest extends TestCase
{
    /** @test */
    public function registration_by_invitation_email()
    {
        $data = [
            'email' => 'hahahahaha'
        ];
        // 邮箱格式不符合的时候，不允许注册
        $this->post('api/register/by_invitation_email/submit_email', $data)
            ->assertStatus(422);

        // qq邮箱不允许注册
        $data['email'] = 'tester@qq.com';
        $this->post('api/register/by_invitation_email/submit_email', $data)
            ->assertStatus(422);

        // .con 报错
        $data['email'] = 'tester@tester.con';
        $this->post('api/register/by_invitation_email/submit_email', $data)
            ->assertStatus(422);

        // 验证返回题目和格式
        $data['email'] = $this->faker->email;
        $response = $this->post('api/register/by_invitation_email/submit_email', $data);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'code',
                'data' => [
                    'registration_application' => [
                        'id',
                        'type',
                        'attributes'
                    ],
                    'quizzes' => [
                        '*' => [
                            'id',
                            'type',
                            'attributes' => [
                                'body',
                                'hint',
                                'options' => [
                                    '*' => [
                                        'type',
                                        'id',
                                        'attributes'
                                    ]
                                ]
                            ]
                        ]
                    ],
                ],
            ]);

    }
}
